<?php

namespace App\Services\Hrm;

use App\Enums\AccountTypeEnum;
use App\Enums\AuditLogActionEnum;
use App\Enums\EmployeeStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Employee;
use App\Models\Tenant\PayrollRun;
use App\Models\Tenant\PayrollSlip;
use App\Models\Tenant\PayrollSlipLine;
use App\Repositories\Hrm\PayrollRunRepository;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class PayrollRunService
{
    public function __construct(
        private PayrollRunRepository $repo,
        private PayrollCalculationService $calculationService,
    ) {}

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

    public function generateSlips(PayrollRun $run): void
    {
        DB::transaction(function () use ($run) {
            $employees = Employee::where('status', EmployeeStatusEnum::ACTIVE->value)->get();

            $existingEmployeeIds = PayrollSlip::where('payroll_run_id', $run->id)->pluck('employee_id')->all();

            foreach ($employees as $employee) {
                if (in_array($employee->id, $existingEmployeeIds)) {
                    continue;
                }

                $result = $this->calculationService->calculateForEmployee($employee, $run->month, $run->year);

                $slip = PayrollSlip::create([
                    'payroll_run_id' => $run->id,
                    'employee_id' => $employee->id,
                    'gross_pay' => $result['gross'],
                    'net_pay' => $result['netPay'],
                ]);

                foreach ($result['lines'] as $line) {
                    PayrollSlipLine::create([
                        'payroll_slip_id' => $slip->id,
                        'type' => $line['type'],
                        'amount' => $line['amount'],
                        'description' => $line['description'],
                    ]);
                }
            }

            $totalPayout = PayrollSlip::where('payroll_run_id', $run->id)->sum('net_pay');
            $run->update(['total_payout' => $totalPayout]);

            AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Payroll run slips generated', 'id' => $run->id]);
        });
    }

    public function postToLedger(PayrollRun $run): void
    {
        DB::transaction(function () use ($run) {
            if ($run->transaction_id) {
                throw new \RuntimeException('Already posted');
            }

            $branchId = admin()?->branch_id ?? null;
            if (!$branchId) {
                throw new \RuntimeException('Unable to determine branch for posting payroll to ledger');
            }

            $expenseAccount = Account::default('Expense', AccountTypeEnum::EXPENSE->value, $branchId);
            $branchCashAccount = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $branchId);

            $transaction = app(TransactionService::class)->create([
                'date' => now(),
                'description' => "Payroll #{$run->month}/{$run->year}",
                'type' => TransactionTypeEnum::EXPENSE->value,
                'reference_type' => PayrollRun::class,
                'reference_id' => $run->id,
                'branch_id' => $branchId,
                'note' => "Payroll run #{$run->id}",
                'amount' => $run->total_payout,
                'lines' => [
                    ['account_id' => $expenseAccount->id, 'type' => 'debit', 'amount' => $run->total_payout],
                    ['account_id' => $branchCashAccount->id, 'type' => 'credit', 'amount' => $run->total_payout],
                ],
            ]);

            $run->update(['transaction_id' => $transaction->id]);

            AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Payroll run posted to ledger', 'id' => $run->id]);
        });
    }
}
