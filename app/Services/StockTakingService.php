<?php

namespace App\Services;

use App\Repositories\StockTakingRepository;

class StockTakingService
{
    public function __construct(private StockTakingRepository $repo) {}

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

    function first($relations = [], $filter = [])
    {
        return $this->repo->first($relations, $filter);
    }

    function save($id = null,$data) {
        if($id) {
            $model = $this->repo->find($id);
            if($model) {
                $model->update($data);
                return $model;
            }
        }

        return $this->repo->create($data);
    }

    function delete($id) {
        $model = $this->repo->find($id);
        if($model) {
            return $model->delete();
        }

        return false;
    }
}
