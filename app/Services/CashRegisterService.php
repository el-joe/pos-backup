<?php

namespace App\Services;

use App\Repositories\BranchRepository;
use App\Repositories\CashRegisterRepository;

class CashRegisterService
{
    public function __construct(private CashRegisterRepository $repo) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function getOpenedCashRegister($relations = [])
    {
        $filters = [
            // 'opened_at' => now(),
            'status'=> 'open',
            'not_closed' => true,
            'admin_id' => admin()->id,
            'branch_id' => branch()?->id,
        ];

        return $this->repo->first($relations, $filters);
    }

    function save($id = null,$data) {
        if($id) {
            $branch = $this->repo->find($id);
            if($branch) {
                $branch->update($data);
                return $branch;
            }
        }

        return $this->repo->create($data);
    }

    function increment($id, $field, $amount = 1) {
        $cashRegister = $this->repo->find($id);
        if($cashRegister) {
            $cashRegister->increment($field, $amount);
            return $cashRegister;
        }

        return null;
    }

    function decrement($id, $field, $amount = 1) {
        $cashRegister = $this->repo->find($id);
        if($cashRegister) {
            $cashRegister->decrement($field, $amount);
            return $cashRegister;
        }

        return null;
    }

    function delete($id) {
        $cashRegister = $this->repo->find($id);
        if($cashRegister) {
            return $cashRegister->delete();
        }

        return false;
    }
}
