<?php

namespace App\Services\Hrm;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Repositories\Hrm\EmployeeContractRepository;
use Illuminate\Support\Facades\DB;

class EmployeeContractService
{
    public function __construct(private EmployeeContractRepository $repo) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function replaceActiveContract(array $data)
    {
        return DB::transaction(function () use ($data) {
            $this->repo->getInstance()
                ->where('employee_id', $data['employee_id'])
                ->update(['is_active' => false]);

            $contract = $this->repo->create($data + ['is_active' => true]);
            AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Employee contract', 'id' => $contract->id]);
            return $contract;
        });
    }
}
