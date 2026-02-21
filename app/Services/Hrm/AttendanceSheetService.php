<?php

namespace App\Services\Hrm;

use App\Models\Tenant\AttendanceSheet;
use App\Repositories\Hrm\AttendanceSheetRepository;

class AttendanceSheetService
{
    public function __construct(private AttendanceSheetRepository $repo) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null): mixed
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [], $filter = []): ?AttendanceSheet
    {
        return $this->repo->find($id, $relations, $filter);
    }

    public function getById($id): ?AttendanceSheet
    {
        return $this->find($id);
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
