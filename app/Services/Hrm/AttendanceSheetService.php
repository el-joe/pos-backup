<?php

namespace App\Services\Hrm;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
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
        $sheet = $this->repo->create($data);
        AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Attendance sheet', 'id' => $sheet->id]);
        return $sheet;
    }

    public function update($id, $data = []): mixed
    {
        $sheet = $this->repo->update($id, $data);
        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Attendance sheet', 'id' => $id]);
        return $sheet;
    }

    public function delete($id): mixed
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Attendance sheet', 'id' => $id]);
        }
        return $deleted;
    }
}
