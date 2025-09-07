<?php

namespace App\Services;

use App\Repositories\DiscountRepository;

class DiscountService
{
    public function __construct(private DiscountRepository $repo) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function find($id, $relations = [], $filter = [])
    {
        return $this->repo->find($id, $relations, $filter);
    }

    function delete($id)
    {
        return $this->repo->delete($id);
    }

    function save($id = null,$data) {
        if($id) {
            $discount = $this->repo->find($id);
            if($discount) {
                $discount->update($data);
                return $discount;
            }
        }

        return $this->repo->create($data);
    }
}
