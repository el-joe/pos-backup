<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Expense;
use App\Repositories\ExpenseRepository;

class ExpenseService
{
    public function __construct(private ExpenseRepository $repo,private PurchaseService $purchaseService,private TransactionService $transactionService) {}

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

    function save($id = null,$data) {
        if($id) {
            $expense = $this->repo->find($id);
            if($expense) {
                $expense->update($data);
            }
        }else{
            $expense = $this->repo->create($data);
        }

        $paymentData = [
            'note'=>'Expense #'. $expense->id,
            'type' => TransactionTypeEnum::EXPENSE->value,
            'reference_type' => Expense::class,
            'reference_id' => $expense->id,
            'branch_id' => $expense->branch_id,
            'amount' => $expense->total,
            'lines' => $this->expenseLines([
                'branch_id' => $expense->branch_id,
                'amount'=> $expense->amount,
                'tax_percentage' => $expense->tax_percentage,
                'total' => $expense->total,
            ])
        ];

        $this->transactionService->create($paymentData);

        return $expense;
    }

    function delete($id) {
        $expense = $this->repo->find($id);
        if($expense) {
            $refundPaymentData = [
                'note'=>'Refund Expense #'. $expense->id,
                'type' => TransactionTypeEnum::EXPENSE_REFUND->value,
                'reference_type' => Expense::class,
                'reference_id' => $expense->id,
                'branch_id' => $expense->branch_id,
                'amount' => $expense->total,
                'lines' => $this->expenseLines([
                    'branch_id' => $expense->branch_id,
                    'amount'=> $expense->total
                ],true)
            ];


            $this->transactionService->create($refundPaymentData);

            $expense->delete();
        }

        return false;
    }

    function expenseLines($data,$reverse = false) {
        $total = $data['amount'];

        $lines[] = $this->purchaseService->createExpenseLine([
            'branch_id' => $data['branch_id'],
            'expenses'=> [
                ['amount'=> $total]
            ]
        ],$reverse);

        if(isset($data['tax_percentage']) && $data['tax_percentage'] > 0) {
            $taxAmount = ($data['amount'] * $data['tax_percentage']) / 100;
            $lines[] = $this->purchaseService->createVatReceivableLine([
                'branch_id' => $data['branch_id'],
                'tax_amount'=> $taxAmount
            ],$reverse);

            $total += $taxAmount;
        }

        $lines[] = $this->purchaseService->createBranchCashLine([
            'branch_id' => $data['branch_id'],
            'grand_total'=> $total
        ],'full_paid',$reverse);

        return $lines;
    }
}
