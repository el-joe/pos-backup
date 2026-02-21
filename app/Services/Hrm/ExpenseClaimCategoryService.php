<?php

namespace App\Services\Hrm;

use App\Repositories\Hrm\ExpenseClaimCategoryRepository;

class ExpenseClaimCategoryService
{
    public function __construct(private ExpenseClaimCategoryRepository $repo) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [], $filter = [])
    {
        return $this->repo->find($id, $relations, $filter);
    }

    public function create($data = [])
    {
        return $this->repo->create($data);
    }

    public function update($id, $data = [])
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
