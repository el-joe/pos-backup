<?php

namespace App\Services;

use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Expense;
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
        private ProductService $productService,
        private ExpenseCategoryService $expenseCategoryService
    ) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function first($id = null, $relations = [])
    {
        return $this->repo->first($relations, ['id' => $id]);
    }

    function save($data) {

        $fromBranch = $this->branchService->find($data['from_branch_id']);
        $toBranch = $this->branchService->find($data['to_branch_id']);

        if(!$fromBranch || !$toBranch) {
            throw new \Exception("Invalid branch selected.");
        }

        $expenseBranchId = $data['expense_paid_branch_id'] ?? $fromBranch->id;

        $stockTransfer = $this->repo->create([
            'from_branch_id' => $fromBranch->id,
            'to_branch_id' => $toBranch->id,
            'transfer_date' => $data['transfer_date'],
            'ref_no' => $data['ref_no'],
            'status' => $data['status'],
            'expense_paid_branch_id' => $expenseBranchId,
        ]);

        $items = $data['items'] ?? $data['products'] ?? [];

        foreach ($items as $item) {
            $this->saveItem($stockTransfer, $item);
        }

        // i want sum of unit_cost*qty from items array

        $items = array_map(function($item) {
            return [
                ...$item,
                'purchase_price' => $item['unit_cost']
            ];
        }, $items);


        $this->makeTransactions($stockTransfer, $fromBranch, $toBranch, $items);

        // Save Expenses if any
        $this->saveExpenses([
            ...$data,
            'stock_transfer_id' => $stockTransfer->id
        ], $expenseBranchId);

        return $stockTransfer;
    }


    function saveItem($stockTransfer, $data) {
        $fromStock = $this->stockService->first([],[
            'product_id' => $data['product_id'],
            'unit_id' => $data['unit_id'],
            'branch_id' => $stockTransfer->from_branch_id
        ]);

        $toStock = $this->stockService->first([],[
            'product_id' => $data['product_id'],
            'unit_id' => $data['unit_id'],
            'branch_id' => $stockTransfer->to_branch_id
        ]);


        $sellPrice = $toStock->sell_price ?? $fromStock->sell_price;
        $unitCost = $toStock->unit_cost ?? $fromStock->unit_cost;

        if(isset($data['update_prices']) && !!$data['update_prices']) {
            $sellPrice = $fromStock->sell_price;
            $unitCost = $fromStock->unit_cost;
        }

        $stockTransfer->items()->create([
            'product_id' => $data['product_id'],
            'unit_id' => $data['unit_id'],
            'qty' => $data['qty'],
            'update_prices' => $data['update_prices'] ?? false,
            'unit_cost' => $unitCost,
            'sell_price' => $sellPrice,
        ]);

        // Deduct stock from source branch
        $this->stockService->addStock($data['product_id'], $data['unit_id'], $data['qty'], $sellPrice, $unitCost, $stockTransfer->to_branch_id);
        $this->stockService->reduceStock($data['product_id'], $data['unit_id'], $data['qty'], $stockTransfer->from_branch_id);
    }

    function makeTransactions($stockTransfer, $fromBranch, $toBranch, $items) {
        $amount = array_sum(array_map(function($item) {
            return $item['unit_cost'] * $item['qty'];
        }, $items)) ?? 0;

        $lines = [
            $this->purchaseService->createInventoryLine([
                'branch_id' => $fromBranch->id,
                'orderProducts' => $items
            ],true),
            $this->purchaseService->createInventoryLine([
                'branch_id' => $toBranch->id,
                'orderProducts' => $items
            ])
        ];

        // Record transactions for both branches
        $transactionData = [
            'description' => "Stock Transfer from {$fromBranch->name} to {$toBranch->name}",
            'type' => TransactionTypeEnum::STOCK_TRANSFER->value,
            'reference_type' => StockTransfer::class,
            'reference_id' => $stockTransfer->id,
            'branch_id' => $fromBranch->id,
            'note' =>  "Stock Transfer from {$fromBranch->name} to {$toBranch->name}",
            'amount' => $amount,
            'lines' => $lines
        ];

        $this->transactionService->create($transactionData);
    }

    function saveExpenses($data, $expenseBranchId , $reverse = false) {

        if(isset($data['expenses']) && is_array($data['expenses'])) {
            $amount = array_sum(array_column($data['expenses'], 'amount'));

            $defaultExpenseCategory = $this->expenseCategoryService->getDefaultCategory('purchase');
            if(!$defaultExpenseCategory){
                $defaultExpenseCategory = $this->expenseCategoryService->save(null,[
                    'name' => 'stock transfer expenses',
                    'default' => 1,
                ]);
            }

            $stockTransfer = $this->find($data['stock_transfer_id'] ?? null);
            if(!$reverse){
                foreach ($data['expenses'] as $item) {
                    $stockTransfer->expenses()->create([
                        'branch_id' => $expenseBranchId,
                        'model_type' => StockTransfer::class,
                        'model_id' => $stockTransfer->id,
                        'expense_category_id' => $defaultExpenseCategory?->id,
                        'amount' => $item['amount'],
                        'note' => $item['description'] ?? $item['note'],
                        'expense_date' => $item['expense_date'],
                    ]);
                }
            }

            $expenseLine = $this->purchaseService->createExpenseLine([
                'expenses' => $data['expenses'],
                'branch_id' => $expenseBranchId
            ], $reverse);

            $branchCashLine = $this->purchaseService->createBranchCashLine([
                'payment_amount' => $amount ?? 0,
                'branch_id' => $expenseBranchId
            ],$reverse);

            $this->transactionService->create([
                'description' => "Expenses ". ($reverse ? 'Refund' : '') ." for Stock Transfer",
                'type' => TransactionTypeEnum::EXPENSE->value,
                'reference_type' => StockTransfer::class,
                'reference_id' => $data['stock_transfer_id'] ?? null,
                'branch_id' => $expenseBranchId,
                'note' => 'Expenses recorded for stock transfer',
                'amount' => $amount ?? 0,
                'lines' => [
                    $expenseLine,
                    $branchCashLine
                ]
            ]);
        }
    }


    function delete($id) {
        $branch = $this->repo->find($id);
        if($branch) {
            return $branch->delete();
        }

        return false;
    }
}
