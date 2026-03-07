<?php

namespace App\Services\Hrm;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Repositories\Hrm\DepartmentRepository;

class DepartmentService
{
    public function __construct(private DepartmentRepository $repo) {}

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
        $department = $this->repo->create($data);
        AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Department', 'id' => $department->id]);
        return $department;
    }

    public function update($id, $data = [])
    {
        $department = $this->repo->update($id, $data);
        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Department', 'id' => $id]);
        return $department;
    }

    public function delete($id)
    {
        $model = $this->repo->find($id);
        $deleted = $model?->delete();
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Department', 'id' => $id]);
        }
        return $deleted;
    }
}
