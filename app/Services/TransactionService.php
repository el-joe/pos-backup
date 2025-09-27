<?php

namespace App\Services;

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
            'description' => $data['description'],
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

    function delete($id) {
        $transaction = $this->repo->find($id);
        if($transaction) {
            return $transaction->delete();
        }

        return false;
    }
}
