<?php

namespace App\Services;

use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\StockTaking;
use App\Models\Tenant\StockTakingProduct;
use App\Repositories\StockTakingRepository;

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

    function save($id = null,$data) {
        $st = $this->repo->create([
            'branch_id' => $data['branch_id'],
            'date' => $data['date'],
            'note' => $data['note'] ?? null,
            'created_by' => admin()?->id
        ]);

        // save stock taking products
        $shortageProducts = [];
        $surplusProducts = [];
        foreach($data['stocks']??[] as $product){
            $st->products()->create([
                'product_id' => $product['product_id'],
                'current_qty' => $product['current_stock'],
                'actual_qty' => $data['countedStock'][$product['product_id']][$product['unit_id']] ?? 0,
                'stock_id' => $product['stock_id'] ?? null,
                'unit_cost' => $product['unit_cost'] ?? 0,
            ]);

            $difference = ($data['countedStock'][$product['product_id']][$product['unit_id']] ?? 0) - $product['current_stock'];
            $product['difference'] = $difference;
            $product['total'] = $product['difference'] * ($product['unit_cost'] ?? 0);
            if($difference < 0){
                $shortageProducts[] = $product;
            }else{
                $surplusProducts[] = $product;
            }

            $stock = $this->stockService->first([],['id' => ($product['stock_id'] ?? null)]);

            if($stock){
                $stock->increment('qty', $difference);
            }
        }

        $this->shortageTransaction($st,$shortageProducts);

        $this->surplusTransaction($st,$surplusProducts);

        return $st;
    }

    function shortageTransaction($st,$shortageProducts, $reverse = false) {
        if(!count($shortageProducts) == 0){
            // shortage transaction
            $description = 'Stock Shortage for #'.$st->id;
            if($reverse){
                $description = 'Return Stock Shortage for '.$st->stock?->product?->name . " - " . $st->stock?->unit?->name;
            }
            $transactionData = [
                'description' => $description,
                'type' => $reverse ? TransactionTypeEnum::STOCK_ADJUSTMENT_REFUND->value : TransactionTypeEnum::STOCK_ADJUSTMENT->value,
                'reference_type' => StockTaking::class,
                'reference_id' => $st->id,
                'branch_id' => $st->branch_id,
                'note' => $st->note ?? '',
                'amount' => array_sum(array_column($shortageProducts, 'total')) * -1,
                'lines' => $this->stockShortageLines([
                    'products' => $shortageProducts,
                    'branch_id' => $st->branch_id,
                    'note' => $st->note ?? '',
                    'orderProducts' => collect($shortageProducts)->map(function ($item){
                        return [
                            'qty' => $item['difference'] * -1,
                            'purchase_price' => $item['unit_cost'] ?? 0,
                        ];
                    })->toArray()
                ], $reverse)
            ];

            $this->transactionService->create($transactionData);
        }
    }

    function surplusTransaction($st,$surplusProducts,$reverse = false) {
        if(!count($surplusProducts) == 0){
            // surplus transaction
            $description = 'Stock Shortage for #'.$st->id;
            if($reverse){
                $description = 'Return Stock Shortage for '.$st->stock?->product?->name . " - " . $st->stock?->unit?->name;
            }
            $transactionData = [
                'description' => $description,
                'type' => $reverse ? TransactionTypeEnum::STOCK_ADJUSTMENT_REFUND->value : TransactionTypeEnum::STOCK_ADJUSTMENT->value,
                'reference_type' => StockTaking::class,
                'reference_id' => $st->id,
                'branch_id' => $st->branch_id,
                'note' => $st->note ?? '',
                'amount' => array_sum(array_column($surplusProducts, 'total')),
                'lines' => $this->stockSurplusLines([
                    'branch_id' => $st->branch_id,
                    'products' => $surplusProducts,
                    'orderProducts' => collect($surplusProducts)->map(function ($item){
                        return [
                            'qty' => $item['difference'],
                            'purchase_price' => $item['unit_cost'] ?? 0,
                        ];
                    })->toArray()
                ], $reverse)
            ];

            $this->transactionService->create($transactionData);
        }
    }

    function returnStock($id) {
        $stProduct = StockTakingProduct::find($id);
        $unitCost = $stProduct->stock?->unit_cost ?? 0;

        $qty = $stProduct->difference > 0 ? $stProduct->difference : ($stProduct->difference * -1);
        if($stProduct->difference < 0){
            $this->shortageTransaction($stProduct->stockTaking, [
                [
                    'difference' => $qty,
                    'unit_cost' => $unitCost,
                    'total' => $qty * $unitCost
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

        $stProduct->stock->increment('qty', $stProduct->difference * -1);

        $stProduct->update([
            'returned' => true
        ]);

        return true;
    }

    function stockShortageLines($data, $reverse = false) {
        // Shortage line
        $lines[] = $this->transactionService->createInventoryShortageLine($data, $reverse);
        $lines[] = $this->purchaseService->createInventoryLine($data,!$reverse);

        return $lines;
    }

    function stockSurplusLines($data, $reverse = false)
    {
        // Surplus line
        $lines[] = $this->purchaseService->createCogsLine($data,!$reverse);
        $lines[] = $this->purchaseService->createInventoryLine($data, $reverse);

        return $lines;
    }

    function delete($id) {
        $model = $this->repo->find($id);
        if($model) {
            return $model->delete();
        }

        return false;
    }
}
