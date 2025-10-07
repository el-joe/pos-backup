<?php

namespace App\Services;

use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\StockTaking;
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
                'stock_id' => $product['stock_id'] ?? null
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


        // shortage transaction
        $transactionData = [
            'description' => 'Stock Shortage for #'.$st->id,
            'type' => TransactionTypeEnum::STOCK_ADJUSTMENT->value,
            'reference_type' => StockTaking::class,
            'reference_id' => $st->id,
            'branch_id' => $st->branch_id,
            'note' => $data['note'] ?? '',
            'amount' => array_sum(array_column($shortageProducts, 'total')) * -1,
            'lines' => $this->stockShortageLines([
                'products' => $shortageProducts,
                'branch_id' => $st->branch_id,
                'note' => $data['note'] ?? '',
                'orderProducts' => collect($shortageProducts)->map(function ($item){
                    return [
                        'qty' => $item['difference'] * -1,
                        'purchase_price' => $item['unit_cost'] ?? 0,
                    ];
                })->toArray()
            ])
        ];

        $this->transactionService->create($transactionData);

        // surplus transaction
        $transactionData = [
            'description' => 'Stock Surplus for #'.$st->id,
            'type' => TransactionTypeEnum::STOCK_ADJUSTMENT->value,
            'reference_type' => StockTaking::class,
            'reference_id' => $st->id,
            'branch_id' => $st->branch_id,
            'note' => $data['note'] ?? '',
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
            ])
        ];

        $this->transactionService->create($transactionData);

        return $st;
    }

    function stockShortageLines($data) {
        // Shortage line
        $lines[] = $this->transactionService->createInventoryShortageLine($data);
        $lines[] = $this->purchaseService->createInventoryLine($data,true);

        return $lines;
    }

    function stockSurplusLines($data)
    {
        // Surplus line
        $lines[] = $this->purchaseService->createCogsLine($data,true);
        $lines[] = $this->purchaseService->createInventoryLine($data);

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
