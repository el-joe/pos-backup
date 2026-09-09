<?php

namespace App\Services;

use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\StockTaking;
use App\Models\Tenant\StockTakingProduct;
use App\Repositories\StockTakingRepository;
use Illuminate\Support\Facades\DB;

class StockTakingService
{
    public function __construct(private StockTakingRepository $repo,private StockService $stockService,private TransactionService $transactionService,private PurchaseService $purchaseService,private SellService $sellService) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function activeList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter + [
            'active' => 1
        ], $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function first($relations = [], $filter = [])
    {
        return $this->repo->first($relations, $filter);
    }

    /**
     * Records the count. This never touches stock quantities or the ledger — a stock
     * taking only takes effect once approve() is called, so a miscount can be corrected
     * (or simply never approved) without having posted anything.
     */
    function save($id = null,$data) {
        return DB::transaction(function () use ($data) {
            $st = $this->repo->create([
                'branch_id' => $data['branch_id'],
                'date' => $data['date'],
                'note' => $data['note'] ?? null,
                'created_by' => admin()?->id
            ]);

            foreach ($data['stocks']??[] as $product) {
                // Value the count at the stock's current weighted-average cost, read fresh
                // from the row — never the client-supplied figure, which may be stale.
                $stock = $product['stock_id'] ?? null
                    ? $this->stockService->first([], ['id' => $product['stock_id']])
                    : null;
                $unitCost = $stock->unit_cost ?? ($product['unit_cost'] ?? 0);

                $st->products()->create([
                    'product_id' => $product['product_id'],
                    'current_qty' => $product['current_stock'],
                    'actual_qty' => $data['countedStock'][$product['product_id']][$product['unit_id']] ?? 0,
                    'stock_id' => $product['stock_id'] ?? null,
                    'unit_cost' => $unitCost,
                ]);
            }

            return $st;
        });
    }

    /**
     * Applies the counted variance to stock and posts the balanced adjustment. Requires
     * an approver so a miscounted draft can never silently move inventory value. Guarded
     * against double-posting via approved_at.
     */
    function approve($id, $approvedBy) {
        return DB::transaction(function () use ($id, $approvedBy) {
            $st = $this->repo->find($id, ['products']);

            if (!$st) {
                throw new \Exception('Stock taking not found.');
            }

            if ($st->approved_at) {
                // Already posted — approving twice must never double-adjust stock or the ledger.
                return $st;
            }

            $shortageProducts = [];
            $surplusProducts = [];

            foreach ($st->products as $stProduct) {
                $difference = (float) $stProduct->difference;
                if ($difference == 0) {
                    continue;
                }

                $stock = $stProduct->stock_id
                    ? $this->stockService->first([], ['id' => $stProduct->stock_id])
                    : null;
                $unitCost = $stock->unit_cost ?? (float) $stProduct->unit_cost;

                $row = [
                    'difference' => $difference,
                    'unit_cost' => $unitCost,
                    'total' => $difference * $unitCost,
                ];

                if ($difference < 0) {
                    $shortageProducts[] = $row;
                    $this->stockService->removeFromStock($stock->product_id, $stock->unit_id, abs($difference), $stock->branch_id);
                } else {
                    $surplusProducts[] = $row;
                    $this->stockService->addStock($stock->product_id, $stock->unit_id, $difference, $stock->sell_price, $stock->unit_cost, $stock->branch_id);
                }
            }

            $this->shortageTransaction($st, $shortageProducts);
            $this->surplusTransaction($st, $surplusProducts);

            $st->update([
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);

            return $st;
        });
    }

    function shortageTransaction($st,$shortageProducts, $reverse = false) {
        if(count($shortageProducts) > 0){
            // shortage transaction
            $description = 'Stock Shortage for #'.$st->id;
            if($reverse){
                $description = 'Return Stock Shortage for '.$st->stock?->product?->name . " - " . $st->stock?->unit?->name;
            }
            $data = [
                'products' => $shortageProducts,
                'branch_id' => $st->branch_id,
                'note' => $st->note ?? '',
            ];
            $transactionData = [
                'description' => $description,
                'type' => $reverse ? TransactionTypeEnum::STOCK_ADJUSTMENT_REFUND->value : TransactionTypeEnum::STOCK_ADJUSTMENT->value,
                'reference_type' => StockTaking::class,
                'reference_id' => $st->id,
                'branch_id' => $st->branch_id,
                'note' => $st->note ?? '',
                'lines' => $this->stockShortageLines($data, $reverse)
            ];

            $this->transactionService->create($transactionData);
        }
    }

    function surplusTransaction($st,$surplusProducts,$reverse = false) {
        if(count($surplusProducts) > 0){
            // surplus transaction
            $description = 'Stock Surplus for #'.$st->id;
            if($reverse){
                $description = 'Return Stock Surplus for '.$st->stock?->product?->name . " - " . $st->stock?->unit?->name;
            }
            $data = [
                'products' => $surplusProducts,
                'branch_id' => $st->branch_id,
                'note' => $st->note ?? '',
            ];
            $transactionData = [
                'description' => $description,
                'type' => $reverse ? TransactionTypeEnum::STOCK_ADJUSTMENT_REFUND->value : TransactionTypeEnum::STOCK_ADJUSTMENT->value,
                'reference_type' => StockTaking::class,
                'reference_id' => $st->id,
                'branch_id' => $st->branch_id,
                'note' => $st->note ?? '',
                'lines' => $this->stockSurplusLines($data, $reverse)
            ];

            $this->transactionService->create($transactionData);
        }
    }

    function returnStock($id) {
        return DB::transaction(function () use ($id) {
            $stProduct = StockTakingProduct::lockForUpdate()->find($id);

            if (!$stProduct) {
                throw new \Exception('Stock taking product not found.');
            }

            if ($stProduct->returned) {
                // Already reversed — do not reverse stock or the ledger a second time.
                return true;
            }

            $unitCost = $stProduct->stock?->unit_cost ?? 0;
            $qty = abs($stProduct->difference);

            if($stProduct->difference < 0){
                $this->shortageTransaction($stProduct->stockTaking, [
                    [
                        'difference' => -$qty,
                        'unit_cost' => $unitCost,
                        'total' => -$qty * $unitCost
                    ]
                ], true);
            }elseif($stProduct->difference > 0){
                $this->surplusTransaction($stProduct->stockTaking, [
                    [
                        'difference' => $qty,
                        'unit_cost' => $unitCost,
                        'total' => $qty * $unitCost
                    ]
                ], true);
            }

            $reverseQty = $stProduct->difference * -1;
            $stock = $stProduct->stock;
            if($reverseQty > 0){
                $this->stockService->addStock($stock->product_id, $stock->unit_id, $reverseQty, $stock->sell_price, $stock->unit_cost, $stock->branch_id);
            }elseif($reverseQty < 0){
                $this->stockService->removeFromStock($stock->product_id, $stock->unit_id, abs($reverseQty), $stock->branch_id);
            }

            $stProduct->update([
                'returned' => true
            ]);

            return true;
        });
    }

    function stockShortageLines($data, $reverse = false) {
        // DR Inventory Shortage / CR Inventory (flipped when reversing)
        return [
            $this->transactionService->createInventoryShortageLine($data, $reverse),
            $this->transactionService->createInventoryAdjustmentLine($data, $reverse ? 'debit' : 'credit'),
        ];
    }

    function stockSurplusLines($data, $reverse = false)
    {
        // DR Inventory / CR Inventory Gain (flipped when reversing)
        return [
            $this->transactionService->createInventoryAdjustmentLine($data, $reverse ? 'credit' : 'debit'),
            $this->transactionService->createInventoryGainLine($data, $reverse),
        ];
    }

    function delete($id) {
        $model = $this->repo->find($id);
        if($model) {
            return $model->delete();
        }

        return false;
    }
}
