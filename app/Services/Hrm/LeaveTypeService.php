<?php

namespace App\Services\Hrm;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Repositories\Hrm\LeaveTypeRepository;

class LeaveTypeService
{
    public function __construct(private LeaveTypeRepository $repo) {}

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
        $leaveType = $this->repo->create($data);
        AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Leave type', 'id' => $leaveType->id]);
        return $leaveType;
    }

    public function update($id, $data = [])
    {
        $leaveType = $this->repo->update($id, $data);
        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Leave type', 'id' => $id]);
        return $leaveType;
    }

    public function delete($id)
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Leave type', 'id' => $id]);
        }
        return $deleted;
    }
}
