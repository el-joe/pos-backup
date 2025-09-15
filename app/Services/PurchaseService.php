<?php

namespace App\Services;

use App\Repositories\BranchRepository;
use App\Repositories\PurchaseRepository;

class PurchaseService
{
    public function __construct(private PurchaseRepository $repo) {}

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
            $purchase = $this->repo->find($id);
            if($purchase) {
                $purchase->update($data);
                return $purchase;
            }
        }

        return $this->repo->create($data);
    }

    function delete($id) {
        $purchase = $this->repo->find($id);
        if($purchase) {
            return $purchase->delete();
        }

        return false;
    }
}
