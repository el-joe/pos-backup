<?php

namespace App\Services;

use App\Repositories\TaxRepository;

class TaxService
{
    public function __construct(private TaxRepository $repo) {}

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
            $tax = $this->repo->find($id);
            if($tax) {
                $tax->update($data);
                return $tax;
            }
        }

        return $this->repo->create($data);
    }
}
