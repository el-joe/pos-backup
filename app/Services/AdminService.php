<?php

namespace App\Services;

use App\Repositories\AdminRepository;
use App\Repositories\BranchRepository;

class AdminService
{
    public function __construct(private AdminRepository $repo) {}

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

    function count($filter = []) {
        return $this->repo->count($filter);
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

    function delete($id) {
        $branch = $this->repo->find($id);
        if($branch) {
            return $branch->delete();
        }

        return false;
    }
}
