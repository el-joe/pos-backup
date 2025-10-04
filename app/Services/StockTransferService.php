<?php

namespace App\Services;

use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\StockTransfer;
use App\Repositories\StockTransferRepository;

class StockTransferService
{
    public function __construct(
        private StockTransferRepository $repo,
        private StockService $stockService,
        private TransactionService $transactionService,
        private BranchService $branchService,
        private PurchaseService $purchaseService,
        private ProductService $productService
    ) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function save($id = null,$data) {

        $fromBranch = $this->branchService->find($data['from_branch_id']);
        $toBranch = $this->branchService->find($data['to_branch_id']);
        $fromStock = $this->stockService->first([],[
            'product_id' => $data['product_id'],
            'unit_id' => $data['unit_id'],
            'branch_id' => $fromBranch->id
        ]);
        $toStock = $this->stockService->first([],[
            'product_id' => $data['product_id'],
            'unit_id' => $data['unit_id'],
            'branch_id' => $toBranch->id
        ]);

        if($id) {
            $branch = $this->repo->find($id);
            if($branch) {
                $branch->update($data);
                return $branch;
            }
        }

        $stockTransfer = $this->repo->create($data);

        $sellPrice = $toStock->sell_price;
        $unitCost = $toStock->unit_cost;

        if(isset($data['can_update_prices']) && $data['can_update_prices']) {
            $sellPrice = $fromStock->sell_price;
            $unitCost = $fromStock->unit_cost;
        }

        // Deduct stock from source branch
        $this->stockService->addStock($data['product_id'], $data['unit_id'], $data['quantity'], $sellPrice, $unitCost, $toBranch->id);
        $this->stockService->reduceStock($data['product_id'], $data['unit_id'], $data['quantity'], $fromBranch->id);

        $amount = $fromStock->unit_cost * $data['quantity'];

        // Record transactions for both branches
        $transactionData = [
            'description' => "Stock Transfer from {$fromBranch->name} to {$toBranch->name}",
            'type' => TransactionTypeEnum::STOCK_TRANSFER->value,
            'reference_type' => StockTransfer::class,
            'reference_id' => $stockTransfer->id,
            'branch_id' => $fromBranch->id,
            'note' => 'Stock transferred from branch #'. $fromBranch->id .' to branch #'. $toBranch->id,
            'amount' => $amount,
            'lines' => [
                $this->purchaseService->createInventoryLine([
                    'branch_id' => $fromBranch->id,
                    'orderProducts' => [
                        [
                            'qty' => $data['quantity'],
                            'purchase_price' => $fromStock->unit_cost,
                        ]
                    ]
                ],true)
            ]
        ];

        $this->transactionService->create($transactionData);

        $toTransactionData = [
            'description' => "Stock Transfer from {$fromBranch->name} to {$toBranch->name}",
            'type' => TransactionTypeEnum::STOCK_TRANSFER->value,
            'reference_type' => StockTransfer::class,
            'reference_id' => $stockTransfer->id,
            'branch_id' => $toBranch->id,
            'note' => 'Stock transferred from branch #'. $fromBranch->id .' to branch #'. $toBranch->id,
            'amount' => $amount,
            'lines' => [
                $this->purchaseService->createInventoryLine([
                    'branch_id' => $toBranch->id,
                    'orderProducts' => [
                        [
                            'qty' => $data['quantity'],
                            'purchase_price' => $fromStock->unit_cost,
                        ]
                    ]
                ])
            ]
        ];

        $this->transactionService->create($toTransactionData);

    }

    function delete($id) {
        $branch = $this->repo->find($id);
        if($branch) {
            return $branch->delete();
        }

        return false;
    }
}
