<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Account;
use App\Repositories\TransactionRepository;

class TransactionService
{
    public function __construct(private TransactionRepository $repo) {}

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

    function create($data) {
        // create transaction
        $transaction = $this->repo->create([
            'date' => now(),
            'description' => $data['description'] ?? $data['note'] ?? '',
            'type' => $data['type'] ?? null,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'branch_id' => $data['branch_id'] ?? null,
            'note' => $data['note'] ?? '',
            'amount' => $data['amount'] ?? 0,
        ]);

        foreach ($data['lines'] as $line) {
            if(($line['amount'] ?? 0) == 0) continue;
            $transaction->lines()->create([
                'account_id' => $line['account_id'],
                'type' => $line['type'] ?? 'debit',
                'amount' => $line['amount'] ?? 0,
            ]);
        }

        return $transaction;
    }


    function createInventoryShortageLine($data,$reverse = false) {
        $getInventoryShortageAccount = Account::default('inventory_shortage', AccountTypeEnum::INVENTORY_SHORTAGE->value,  $data['branch_id']);
        // get sub total from order products = product qty * unit cost
        $subTotal = array_sum(array_map(function($item) {
            return $item['difference'] * (float)$item['unit_cost'];
        }, $data['products']));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getInventoryShortageAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $subTotal,
        ];
    }


    function delete($id) {
        $transaction = $this->repo->find($id);
        if($transaction) {
            return $transaction->delete();
        }

        return false;
    }
}
