<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\CheckDirectionEnum;
use App\Enums\CheckStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Check;
use Illuminate\Support\Facades\DB;

class CheckService
{
    public function __construct(private TransactionService $transactionService) {}

    public function collect(int $checkId, ?int $collectedAccountId = null, ?string $note = null): Check
    {
        /** @var Check $check */
        $check = Check::with(['payable', 'customer', 'branch'])->findOrFail($checkId);

        if ($check->direction !== CheckDirectionEnum::RECEIVED->value) {
            throw new \RuntimeException('Only received checks can be collected');
        }
        if ($check->status !== CheckStatusEnum::UNDER_COLLECTION->value) {
            throw new \RuntimeException('Check is not under collection');
        }

        return DB::transaction(function () use ($check, $collectedAccountId, $note) {
            $branchId = $check->branch_id;

            $checksUnderCollection = Account::default('Checks Under Collection', AccountTypeEnum::CHECKS_UNDER_COLLECTION->value, $branchId);
            $bankOrCash = $collectedAccountId
                ? Account::findOrFail($collectedAccountId)
                : Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $branchId);

            $this->transactionService->create([
                'date' => now(),
                'description' => 'Collect Check'.($check->check_number ? ' #'.$check->check_number : ''),
                'type' => TransactionTypeEnum::CHECK_COLLECTION->value,
                'reference_type' => Check::class,
                'reference_id' => $check->id,
                'branch_id' => $branchId,
                'note' => $note ?? $check->note ?? '',
                'amount' => (float)$check->amount,
                'lines' => [
                    [
                        'account_id' => $bankOrCash->id,
                        'type' => 'debit',
                        'amount' => (float)$check->amount,
                    ],
                    [
                        'account_id' => $checksUnderCollection->id,
                        'type' => 'credit',
                        'amount' => (float)$check->amount,
                    ],
                ],
            ]);

            $check->update([
                'status' => CheckStatusEnum::COLLECTED->value,
                'collected_account_id' => $bankOrCash->id,
                'collected_at' => now(),
            ]);

            return $check->refresh();
        });
    }

    public function bounce(int $checkId, ?string $note = null): Check
    {
        /** @var Check $check */
        $check = Check::with(['customer', 'branch'])->findOrFail($checkId);

        if ($check->direction !== CheckDirectionEnum::RECEIVED->value) {
            throw new \RuntimeException('Only received checks can bounce');
        }
        if ($check->status !== CheckStatusEnum::UNDER_COLLECTION->value) {
            throw new \RuntimeException('Only under-collection checks can bounce');
        }
        if (!$check->customer_id) {
            throw new \RuntimeException('Bounced check requires customer');
        }

        return DB::transaction(function () use ($check, $note) {
            $branchId = $check->branch_id;

            $checksUnderCollection = Account::default('Checks Under Collection', AccountTypeEnum::CHECKS_UNDER_COLLECTION->value, $branchId);

            // Customer receivable account
            $customerAccount = Account::where('model_type', \App\Models\Tenant\User::class)
                ->where('model_id', $check->customer_id)
                ->where('type', AccountTypeEnum::CUSTOMER->value)
                ->first();

            if (!$customerAccount) {
                $customer = \App\Models\Tenant\User::findOrFail($check->customer_id);
                $customerAccount = app(AccountService::class)->createAccountForUser($customer);
            }

            $this->transactionService->create([
                'date' => now(),
                'description' => 'Bounced Check'.($check->check_number ? ' #'.$check->check_number : ''),
                'type' => TransactionTypeEnum::CHECK_BOUNCE->value,
                'reference_type' => Check::class,
                'reference_id' => $check->id,
                'branch_id' => $branchId,
                'note' => $note ?? $check->note ?? '',
                'amount' => (float)$check->amount,
                'lines' => [
                    [
                        'account_id' => $customerAccount->id,
                        'type' => 'debit',
                        'amount' => (float)$check->amount,
                    ],
                    [
                        'account_id' => $checksUnderCollection->id,
                        'type' => 'credit',
                        'amount' => (float)$check->amount,
                    ],
                ],
            ]);

            $check->update([
                'status' => CheckStatusEnum::BOUNCED->value,
                'bounced_at' => now(),
            ]);

            return $check->refresh();
        });
    }

    public function clearIssued(int $checkId, ?int $clearedAccountId = null, ?string $note = null): Check
    {
        /** @var Check $check */
        $check = Check::with(['payable', 'supplier', 'branch'])->findOrFail($checkId);

        if ($check->direction !== CheckDirectionEnum::ISSUED->value) {
            throw new \RuntimeException('Only issued checks can be cleared');
        }
        if ($check->status !== CheckStatusEnum::ISSUED->value) {
            throw new \RuntimeException('Check is not in issued status');
        }

        return DB::transaction(function () use ($check, $clearedAccountId, $note) {
            $branchId = $check->branch_id;

            $issuedChecks = Account::default('Issued Checks', AccountTypeEnum::ISSUED_CHECKS->value, $branchId);
            $bankOrCash = $clearedAccountId
                ? Account::findOrFail($clearedAccountId)
                : Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $branchId);

            $this->transactionService->create([
                'date' => now(),
                'description' => 'Clear Issued Check'.($check->check_number ? ' #'.$check->check_number : ''),
                'type' => TransactionTypeEnum::CHECK_CLEARING->value,
                'reference_type' => Check::class,
                'reference_id' => $check->id,
                'branch_id' => $branchId,
                'note' => $note ?? $check->note ?? '',
                'amount' => (float)$check->amount,
                'lines' => [
                    [
                        'account_id' => $issuedChecks->id,
                        'type' => 'debit',
                        'amount' => (float)$check->amount,
                    ],
                    [
                        'account_id' => $bankOrCash->id,
                        'type' => 'credit',
                        'amount' => (float)$check->amount,
                    ],
                ],
            ]);

            $check->update([
                'status' => CheckStatusEnum::CLEARED->value,
                'cleared_account_id' => $bankOrCash->id,
                'cleared_at' => now(),
            ]);

            return $check->refresh();
        });
    }
}
