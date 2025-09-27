<?php

namespace App\Services;

use App\Repositories\ExpenseRepository;

class ExpenseService
{
    public function __construct(private ExpenseRepository $repo,private PurchaseService $purchaseService) {}

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
                return $expense;
            }
        }

        return $this->repo->create($data);
    }

    function delete($id) {
        $expense = $this->repo->find($id);
        if($expense) {
            return $expense->delete();
        }

        return false;
    }
}
