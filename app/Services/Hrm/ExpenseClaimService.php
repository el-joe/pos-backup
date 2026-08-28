<?php

namespace App\Services\Hrm;

use App\Enums\AccountTypeEnum;
use App\Enums\AuditLogActionEnum;
use App\Enums\ExpenseClaimStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\ExpenseClaim;
use App\Models\Tenant\ExpenseClaimLine;
use App\Repositories\Hrm\ExpenseClaimRepository;
use App\Services\CashRegisterService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class ExpenseClaimService
{
    public function __construct(private ExpenseClaimRepository $repo) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [], $filter = [])
    {
        return $this->repo->find($id, $relations, $filter);
    }

    public function createWithLines(array $claimData, array $lines = [])
    {
        return DB::transaction(function () use ($claimData, $lines) {
            $claim = $this->repo->create($claimData);

            $total = 0;
            foreach ($lines as $line) {
                $amount = (float) ($line['amount'] ?? 0);
                $total += $amount;
                ExpenseClaimLine::create([
                    'expense_claim_id' => $claim->id,
                    'category_id' => $line['category_id'] ?? null,
                    'amount' => $amount,
                    'description' => $line['description'] ?? null,
                    'receipt_path' => $line['receipt_path'] ?? null,
                ]);
            }

            $claim->update(['total_amount' => $total]);

            AuditLog::log(AuditLogActionEnum::from('create_record'), ['entity' => 'Expense claim', 'id' => $claim->id]);

            return $claim->refresh();
        });
    }

    public function update($id, $data = [])
    {
        $claim = $this->repo->update($id, $data);
        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Expense claim', 'id' => $id]);

        return $claim;
    }

    public function approve(int $claimId, int $approverId): ExpenseClaim
    {
        $claim = $this->repo->find($claimId);
        if (!$claim) {
            throw new \RuntimeException('Expense claim not found');
        }
        if ($claim->status !== ExpenseClaimStatusEnum::SUBMITTED) {
            throw new \RuntimeException('Only submitted claims can be approved');
        }

        $claim->update([
            'status' => ExpenseClaimStatusEnum::APPROVED->value,
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);

        AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Expense claim approved', 'id' => $claimId]);

        return $claim->refresh();
    }

    public function pay(int $claimId, int $paymentAccountId): ExpenseClaim
    {
        return DB::transaction(function () use ($claimId, $paymentAccountId) {
            $claim = $this->repo->find($claimId, ['employee']);
            if (!$claim) {
                throw new \RuntimeException('Expense claim not found');
            }
            if ($claim->status !== ExpenseClaimStatusEnum::APPROVED) {
                throw new \RuntimeException('Only approved claims can be paid');
            }
            if ($claim->transaction_id) {
                throw new \RuntimeException('This claim has already been paid');
            }

            $expenseAccount = Account::default('Expense', AccountTypeEnum::EXPENSE->value, admin()->branch_id);
            $paymentAccount = Account::findOrFail($paymentAccountId);

            $transaction = app(TransactionService::class)->create([
                'date' => now(),
                'description' => 'Expense Claim Payment for Employee: ' . ($claim->employee?->name ?? $claimId),
                'type' => TransactionTypeEnum::EXPENSE->value,
                'reference_type' => ExpenseClaim::class,
                'reference_id' => $claim->id,
                'branch_id' => admin()->branch_id,
                'note' => 'Expense claim #' . $claimId,
                'amount' => $claim->total_amount,
                'lines' => [
                    ['account_id' => $expenseAccount->id, 'type' => 'debit', 'amount' => $claim->total_amount],
                    ['account_id' => $paymentAccount->id, 'type' => 'credit', 'amount' => $claim->total_amount],
                ],
            ]);

            $claim->update([
                'status' => ExpenseClaimStatusEnum::PAID->value,
                'transaction_id' => $transaction->id,
            ]);

            $cashRegisterService = app(CashRegisterService::class);
            $cashRegister = $cashRegisterService->getOpenedCashRegister();
            if ($cashRegister) {
                $cashRegisterService->increment($cashRegister->id, 'total_expenses', $claim->total_amount);
            }

            AuditLog::log(AuditLogActionEnum::from('update_record'), ['entity' => 'Expense claim paid', 'id' => $claimId]);

            return $claim->refresh();
        });
    }

    public function delete($id)
    {
        $deleted = $this->repo->delete($id);
        if ($deleted) {
            AuditLog::log(AuditLogActionEnum::from('delete_record'), ['entity' => 'Expense claim', 'id' => $id]);
        }

        return $deleted;
    }
}
