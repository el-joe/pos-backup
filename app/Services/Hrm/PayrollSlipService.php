<?php

namespace App\Services\Hrm;

use App\Repositories\Hrm\PayrollSlipRepository;

class PayrollSlipService
{
    public function __construct(private PayrollSlipRepository $repo) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [], $filter = [])
    {
        return $this->repo->find($id, $relations, $filter);
    }
}
