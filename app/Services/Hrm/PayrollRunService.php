<?php

namespace App\Services\Hrm;

use App\Models\Tenant\PayrollRun;
use App\Repositories\Hrm\PayrollRunRepository;

class PayrollRunService
{
    public function __construct(private PayrollRunRepository $repo) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null): mixed
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [], $filter = []): ?PayrollRun
    {
        return $this->repo->find($id, $relations, $filter);
    }

    public function create($data = []): mixed
    {
        return $this->repo->create($data);
    }

    public function update($id, $data = []): mixed
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id): mixed
    {
        return $this->repo->delete($id);
    }
}
