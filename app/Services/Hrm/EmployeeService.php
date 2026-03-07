<?php

namespace App\Services\Hrm;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Repositories\Hrm\EmployeeRepository;

class EmployeeService
{
    public function __construct(private EmployeeRepository $repo) {}

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
        $employee = $this->repo->create($data);
        AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Employee', 'id' => $employee->id]);
        return $employee;
    }

    public function update($id, $data = [])
    {
        $employee = $this->repo->update($id, $data);
        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Employee', 'id' => $id]);
        return $employee;
    }

    public function delete($id)
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Employee', 'id' => $id]);
        }
        return $deleted;
    }
}
