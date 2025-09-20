<?php

namespace App\Services;

use App\Repositories\ExpenseCategoryRepository;

class ExpenseCategoryService
{
    public function __construct(private ExpenseCategoryRepository $repo) {}

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

    function getDefaultCategory($name) {
        return $this->repo->first([],[
            'default' => 1,
            'name' => $name
        ]);
    }
}
