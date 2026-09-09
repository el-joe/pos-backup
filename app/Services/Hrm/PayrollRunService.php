<?php

namespace App\Services\Hrm;

use App\Enums\AccountTypeEnum;
use App\Enums\AuditLogActionEnum;
use App\Enums\EmployeeStatusEnum;
use App\Enums\PayrollRunStatusEnum;
use App\Enums\PayrollSlipLineTypeEnum;
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
        $run = $this->repo->find($id);

        if ($run && ($run->transaction_id || $run->approved_transaction_id)) {
            throw new \RuntimeException('This payroll run has already been posted to the ledger. Reverse it before deleting.');
        }

        $deleted = $this->repo->delete($id);
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Payroll run', 'id' => $id]);
        }
        return $deleted;
    }

    /**
     * Reverses a posted (approved/paid) run's ledger entries and returns it to draft,
     * so it can then be safely deleted. Mirrors the reversal convention used elsewhere
     * in the codebase (e.g. Expense/PurchaseService "_refund" transaction types), by
     * posting the mirror-image lines rather than mutating/deleting the original entries.
     */
    public function reverse(PayrollRun $run, ?int $branchId = null): void
    {
        DB::transaction(function () use ($run, $branchId) {
            $branchId = $branchId ?? $run->branch_id;

            if ($run->transaction_id) {
                $this->reverseTransaction($run->transaction_id, TransactionTypeEnum::PAYROLL_PAYMENT, $run, $branchId, 'Reversal of Payroll Payment');
            }

            if ($run->approved_transaction_id) {
                $this->reverseTransaction($run->approved_transaction_id, TransactionTypeEnum::PAYROLL, $run, $branchId, 'Reversal of Payroll Accrual');
            }

            $run->update([
                'transaction_id' => null,
                'approved_transaction_id' => null,
                'status' => PayrollRunStatusEnum::DRAFT->value,
            ]);

            AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Payroll run reversed', 'id' => $run->id]);
        });
    }

    private function reverseTransaction(int $transactionId, TransactionTypeEnum $type, PayrollRun $run, ?int $branchId, string $description): void
    {
        $original = app(TransactionService::class)->find($transactionId, ['lines']);
        if (!$original) {
            return;
        }

        $lines = $original->lines->map(fn($line) => [
            'account_id' => $line->account_id,
            'type' => $line->type === 'debit' ? 'credit' : 'debit',
            'amount' => $line->amount,
        ])->all();

        app(TransactionService::class)->create([
            'date' => now(),
            'description' => $description . " #{$run->id}",
            'type' => $type->value,
            'reference_type' => PayrollRun::class,
            'reference_id' => $run->id,
            'branch_id' => $branchId,
            'note' => "Reversal of payroll run #{$run->id}",
            'amount' => $original->amount,
            'lines' => $lines,
        ]);
    }

    public function generateSlips(PayrollRun $run): void
    {
        DB::transaction(function () use ($run) {
            $employees = Employee::where('status', EmployeeStatusEnum::ACTIVE->value)
                ->when($run->branch_id, fn($q) => $q->where('branch_id', $run->branch_id))
                ->get();

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

    /**
     * Stage 1 — accrual. Posts the payroll cost/liabilities to the ledger:
     *   DR Salaries Expense (gross)
     *   DR Employer Contributions Expense (employer statutory cost)
     *     CR Salaries Payable (net pay owed to employees)
     *     CR Social Insurance Payable (employee + employer share)
     *     CR Income Tax Payable (withheld)
     *
     * Requires the run to be in 'approved' status and not already posted, so it can never
     * double-post. $branchId must be passed explicitly (defaults to the run's own branch)
     * so this works from console/queue context where admin()/session state isn't available.
     */
    public function approve(PayrollRun $run, ?int $branchId = null): void
    {
        DB::transaction(function () use ($run, $branchId) {
            $run = $run->fresh();

            if ($run->approved_transaction_id) {
                throw new \RuntimeException('Payroll run already posted to the ledger.');
            }

            if (($run->status?->value ?? $run->status) !== PayrollRunStatusEnum::APPROVED->value) {
                throw new \RuntimeException('Only approved payroll runs can be posted to the ledger.');
            }

            $branchId = $branchId ?? $run->branch_id;
            if (!$branchId) {
                throw new \RuntimeException('Unable to determine branch for posting payroll to ledger.');
            }

            $slips = PayrollSlip::where('payroll_run_id', $run->id)->with('lines')->get();
            if ($slips->isEmpty()) {
                throw new \RuntimeException('No payroll slips generated for this run yet.');
            }

            $grossTotal = round((float) $slips->sum('gross_pay'), 2);
            $netTotal = round((float) $slips->sum('net_pay'), 2);

            $socialInsuranceEmployee = 0.0;
            $socialInsuranceEmployer = 0.0;
            $incomeTax = 0.0;

            foreach ($slips as $slip) {
                foreach ($slip->lines as $line) {
                    $type = $line->type instanceof PayrollSlipLineTypeEnum ? $line->type : PayrollSlipLineTypeEnum::from($line->type);
                    match ($type) {
                        PayrollSlipLineTypeEnum::SOCIAL_INSURANCE_EMPLOYEE => $socialInsuranceEmployee += abs((float) $line->amount),
                        PayrollSlipLineTypeEnum::SOCIAL_INSURANCE_EMPLOYER => $socialInsuranceEmployer += abs((float) $line->amount),
                        PayrollSlipLineTypeEnum::INCOME_TAX => $incomeTax += abs((float) $line->amount),
                        default => null,
                    };
                }
            }

            $employerContributions = round($socialInsuranceEmployer, 2);
            $socialInsurancePayable = round($socialInsuranceEmployee + $socialInsuranceEmployer, 2);
            $incomeTaxPayable = round($incomeTax, 2);

            $salariesExpenseAccount = Account::default(AccountTypeEnum::SALARIES_EXPENSE->label(), AccountTypeEnum::SALARIES_EXPENSE->value, $branchId);
            $employerContributionsAccount = Account::default(AccountTypeEnum::EMPLOYER_CONTRIBUTIONS_EXPENSE->label(), AccountTypeEnum::EMPLOYER_CONTRIBUTIONS_EXPENSE->value, $branchId);
            $salariesPayableAccount = Account::default(AccountTypeEnum::SALARIES_PAYABLE->label(), AccountTypeEnum::SALARIES_PAYABLE->value, $branchId);
            $socialInsurancePayableAccount = Account::default(AccountTypeEnum::SOCIAL_INSURANCE_PAYABLE->label(), AccountTypeEnum::SOCIAL_INSURANCE_PAYABLE->value, $branchId);
            $incomeTaxPayableAccount = Account::default(AccountTypeEnum::INCOME_TAX_PAYABLE->label(), AccountTypeEnum::INCOME_TAX_PAYABLE->value, $branchId);

            $lines = [
                ['account_id' => $salariesExpenseAccount->id, 'type' => 'debit', 'amount' => $grossTotal],
            ];

            if ($employerContributions > 0) {
                $lines[] = ['account_id' => $employerContributionsAccount->id, 'type' => 'debit', 'amount' => $employerContributions];
            }

            $lines[] = ['account_id' => $salariesPayableAccount->id, 'type' => 'credit', 'amount' => $netTotal];

            if ($socialInsurancePayable > 0) {
                $lines[] = ['account_id' => $socialInsurancePayableAccount->id, 'type' => 'credit', 'amount' => $socialInsurancePayable];
            }

            if ($incomeTaxPayable > 0) {
                $lines[] = ['account_id' => $incomeTaxPayableAccount->id, 'type' => 'credit', 'amount' => $incomeTaxPayable];
            }

            $totalAmount = $grossTotal + $employerContributions;

            $transaction = app(TransactionService::class)->create([
                'date' => now(),
                'description' => "Payroll Accrual #{$run->month}/{$run->year}",
                'type' => TransactionTypeEnum::PAYROLL->value,
                'reference_type' => PayrollRun::class,
                'reference_id' => $run->id,
                'branch_id' => $branchId,
                'note' => "Payroll run #{$run->id} accrual",
                'amount' => $totalAmount,
                'lines' => $lines,
            ]);

            $run->update([
                'approved_transaction_id' => $transaction->id,
                'branch_id' => $branchId,
                'total_payout' => $netTotal,
            ]);

            AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Payroll run accrual posted to ledger', 'id' => $run->id]);
        });
    }

    /**
     * Stage 2 — payment. Settles the previously accrued Salaries Payable against a
     * payment-capable account (branch cash by default, or any Account with a payment
     * method attached — same "payment_account" convention used by purchase/expense payments):
     *   DR Salaries Payable
     *     CR Payment Account (cash/bank/other)
     *
     * Only sets status='paid' here, and only once accrual (approve()) has been posted.
     */
    public function pay(PayrollRun $run, ?int $branchId = null, ?int $paymentAccountId = null): void
    {
        DB::transaction(function () use ($run, $branchId, $paymentAccountId) {
            $run = $run->fresh();

            if (!$run->approved_transaction_id) {
                throw new \RuntimeException('Payroll run must be posted to the ledger (approved) before it can be paid.');
            }

            if ($run->transaction_id) {
                throw new \RuntimeException('Payroll run already paid.');
            }

            $branchId = $branchId ?? $run->branch_id;
            if (!$branchId) {
                throw new \RuntimeException('Unable to determine branch for posting payroll payment to ledger.');
            }

            $salariesPayableAccount = Account::default(AccountTypeEnum::SALARIES_PAYABLE->label(), AccountTypeEnum::SALARIES_PAYABLE->value, $branchId);

            $paymentAccount = $paymentAccountId
                ? Account::find($paymentAccountId)
                : Account::default(AccountTypeEnum::BRANCH_CASH->label(), AccountTypeEnum::BRANCH_CASH->value, $branchId);

            if (!$paymentAccount) {
                throw new \RuntimeException('Unable to resolve a payment account for payroll payment.');
            }

            $netTotal = round((float) $run->total_payout, 2);

            $transaction = app(TransactionService::class)->create([
                'date' => now(),
                'description' => "Payroll Payment #{$run->month}/{$run->year}",
                'type' => TransactionTypeEnum::PAYROLL_PAYMENT->value,
                'reference_type' => PayrollRun::class,
                'reference_id' => $run->id,
                'branch_id' => $branchId,
                'note' => "Payroll run #{$run->id} payment",
                'amount' => $netTotal,
                'lines' => [
                    ['account_id' => $salariesPayableAccount->id, 'type' => 'debit', 'amount' => $netTotal],
                    ['account_id' => $paymentAccount->id, 'type' => 'credit', 'amount' => $netTotal],
                ],
            ]);

            $run->update([
                'transaction_id' => $transaction->id,
                'status' => PayrollRunStatusEnum::PAID->value,
            ]);

            AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Payroll run payment posted to ledger', 'id' => $run->id]);
        });
    }
}
