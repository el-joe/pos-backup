<?php

namespace App\Services\Hrm;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
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
        $run = $this->repo->create($data);
        AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Payroll run', 'id' => $run->id]);
        return $run;
    }

    public function update($id, $data = []): mixed
    {
        $run = $this->repo->update($id, $data);
        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Payroll run', 'id' => $id]);
        return $run;
    }

    public function delete($id): mixed
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Payroll run', 'id' => $id]);
        }
        return $deleted;
    }
}
