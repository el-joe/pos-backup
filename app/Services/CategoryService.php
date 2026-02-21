<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(private CategoryRepository $repo) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function activeList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter + [
            'active' => 1,
            'empty_parent_id' => true
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
            $branch = $this->repo->find($id);
            if($branch) {
                $branch->update($data);
                return $branch;
            }
        }

        return $this->repo->create($data);
    }
}
