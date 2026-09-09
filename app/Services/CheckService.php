<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\CheckDirectionEnum;
use App\Enums\CheckStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Check;
use App\Models\Tenant\User;
use Illuminate\Support\Facades\DB;

class CheckService
{
    public function __construct(
        private TransactionService $transactionService,
        private SellService $sellService,
        private PurchaseService $purchaseService,
    ) {}

    public function collect(int $checkId, ?int $collectedAccountId = null, ?string $note = null): Check
    {
        /** @var Check $check */
        $check = Check::with(['payable', 'customer', 'branch'])->findOrFail($checkId);

        if (!$check->check_number) {
            throw new \RuntimeException('Check number is required before processing this check');
        }
        if ($check->direction !== CheckDirectionEnum::RECEIVED->value) {
            throw new \RuntimeException('Only received checks can be collected');
        }
        if ($check->status !== CheckStatusEnum::UNDER_COLLECTION->value) {
            throw new \RuntimeException('Check is not under collection');
        }
        if (!$check->customer_id) {
            throw new \RuntimeException('Check requires a customer to be processed');
        }

        return DB::transaction(function () use ($check, $collectedAccountId, $note) {
            $branchId = $check->branch_id;

            $checksUnderCollection = Account::forCheckDirection('received', $branchId);
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

    /**
     * Bounce a received check, from either UNDER_COLLECTION (still sitting in the checks
     * control account) or COLLECTED (bank already credited the collection account and later
     * returned it). Both cases restore AR *and* reverse the sub-ledger (order_payments +
     * sales.paid_amount) through SellService::addPayment(reverse: true) so there is one code
     * path for "a sale payment got un-paid", not two.
     */
    public function bounce(int $checkId, ?string $note = null, ?float $bankCharge = null, ?int $bankChargeAccountId = null): Check
    {
        /** @var Check $check */
        $check = Check::with(['customer', 'branch', 'orderPayment', 'collectedAccount'])->findOrFail($checkId);

        if (!$check->check_number) {
            throw new \RuntimeException('Check number is required before processing this check');
        }
        if ($check->direction !== CheckDirectionEnum::RECEIVED->value) {
            throw new \RuntimeException('Only received checks can bounce');
        }
        if (!in_array($check->status, [CheckStatusEnum::UNDER_COLLECTION->value, CheckStatusEnum::COLLECTED->value], true)) {
            throw new \RuntimeException('Only an under-collection or collected check can bounce');
        }
        if (!$check->customer_id) {
            throw new \RuntimeException('Check requires a customer to be processed');
        }

        return DB::transaction(function () use ($check, $note, $bankCharge, $bankChargeAccountId) {
            $branchId = $check->branch_id;

            // The account to reverse against: the check-tender account (routes into Checks
            // Under Collection) if it never got collected, otherwise the account the bank
            // actually credited at collection time.
            $reversalAccountId = $check->status === CheckStatusEnum::COLLECTED->value
                ? $check->collected_account_id
                : ($check->orderPayment->account_id ?? null);

            if (!$reversalAccountId) {
                throw new \RuntimeException('Unable to resolve the account to reverse this check against');
            }

            $this->sellService->addPayment($check->payable_id, [
                'payments' => [[
                    'account_id' => $reversalAccountId,
                    'amount' => (float)$check->amount,
                ]],
                'customer_id' => $check->customer_id,
                'branch_id' => $branchId,
                'payment_note' => $note ?? $check->note ?? ('Bounced Check #'.$check->check_number),
            ], true, [
                'description' => 'Bounced Check'.($check->check_number ? ' #'.$check->check_number : ''),
                'type' => TransactionTypeEnum::CHECK_BOUNCE->value,
                'reference_type' => Check::class,
                'reference_id' => $check->id,
            ]);

            if ($bankCharge && $bankCharge > 0) {
                $this->postBankCharge($branchId, $bankCharge, $bankChargeAccountId, $check);
            }

            $check->update([
                'status' => CheckStatusEnum::BOUNCED->value,
                'bounced_at' => now(),
                'bank_charge' => $bankCharge ?: $check->bank_charge,
            ]);

            return $check->refresh();
        });
    }

    public function clearIssued(int $checkId, ?int $clearedAccountId = null, ?string $note = null): Check
    {
        /** @var Check $check */
        $check = Check::with(['payable', 'supplier', 'branch'])->findOrFail($checkId);

        if (!$check->check_number) {
            throw new \RuntimeException('Check number is required before processing this check');
        }
        if ($check->direction !== CheckDirectionEnum::ISSUED->value) {
            throw new \RuntimeException('Only issued checks can be cleared');
        }
        if ($check->status !== CheckStatusEnum::ISSUED->value) {
            throw new \RuntimeException('Check is not in issued status');
        }
        if (!$check->supplier_id && $check->payable_type === \App\Models\Tenant\Purchase::class) {
            throw new \RuntimeException('Issued check requires a supplier to be processed');
        }

        return DB::transaction(function () use ($check, $clearedAccountId, $note) {
            $branchId = $check->branch_id;

            $issuedChecks = Account::forCheckDirection('issued', $branchId);
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

    /**
     * Bounce an issued check, from either ISSUED (still sitting in the Issued Checks control
     * account) or CLEARED (the bank already debited the clearing account and later returned
     * it). Mirrors bounce() for purchases: reverses through PurchaseService::addPayment so
     * purchases.paid_amount and the purchase status stay in sync with the GL.
     */
    public function bounceIssued(int $checkId, ?string $note = null, ?float $bankCharge = null, ?int $bankChargeAccountId = null): Check
    {
        /** @var Check $check */
        $check = Check::with(['payable', 'supplier', 'branch', 'orderPayment', 'clearedAccount'])->findOrFail($checkId);

        if (!$check->check_number) {
            throw new \RuntimeException('Check number is required before processing this check');
        }
        if ($check->direction !== CheckDirectionEnum::ISSUED->value) {
            throw new \RuntimeException('Only issued checks can be bounced as issued');
        }
        if (!in_array($check->status, [CheckStatusEnum::ISSUED->value, CheckStatusEnum::CLEARED->value], true)) {
            throw new \RuntimeException('Only an issued or cleared check can be bounced');
        }

        return DB::transaction(function () use ($check, $note, $bankCharge, $bankChargeAccountId) {
            $branchId = $check->branch_id;

            $reversalAccountId = $check->status === CheckStatusEnum::CLEARED->value
                ? $check->cleared_account_id
                : ($check->orderPayment->account_id ?? null);

            if (!$reversalAccountId) {
                throw new \RuntimeException('Unable to resolve the account to reverse this check against');
            }

            if ($check->payable_type === \App\Models\Tenant\Purchase::class) {
                $this->purchaseService->addPayment($check->payable_id, [
                    'payment_account' => $reversalAccountId,
                    'payment_status' => 'partial_paid',
                    'payment_amount' => (float)$check->amount,
                    'supplier_id' => $check->supplier_id,
                    'branch_id' => $branchId,
                    'payment_note' => $note ?? $check->note ?? ('Bounced Issued Check #'.$check->check_number),
                ], true, [
                    'description' => 'Bounced Issued Check'.($check->check_number ? ' #'.$check->check_number : ''),
                    'type' => TransactionTypeEnum::CHECK_BOUNCE->value,
                    'reference_type' => Check::class,
                    'reference_id' => $check->id,
                ]);
            } else {
                // Not a purchase payable (e.g. FixedAsset) — no purchases sub-ledger to
                // reverse, just post the GL entry directly against the resolved account.
                $issuedChecks = Account::forCheckDirection('issued', $branchId);
                $reversalAccount = Account::findOrFail($reversalAccountId);

                $this->transactionService->create([
                    'date' => now(),
                    'description' => 'Bounced Issued Check'.($check->check_number ? ' #'.$check->check_number : ''),
                    'type' => TransactionTypeEnum::CHECK_BOUNCE->value,
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
                            'account_id' => $reversalAccount->id,
                            'type' => 'credit',
                            'amount' => (float)$check->amount,
                        ],
                    ],
                ]);
            }

            if ($bankCharge && $bankCharge > 0) {
                $this->postBankCharge($branchId, $bankCharge, $bankChargeAccountId, $check);
            }

            $check->update([
                'status' => CheckStatusEnum::BOUNCED->value,
                'bounced_at' => now(),
                'bank_charge' => $bankCharge ?: $check->bank_charge,
            ]);

            return $check->refresh();
        });
    }

    /**
     * Re-present a bounced check: moves it back to UNDER_COLLECTION (received) or ISSUED
     * (issued) and re-posts the asset/liability entry, as if the check were freshly received
     * or issued again. Optionally records that this check was replaced by a new check number
     * instead of being re-presented as-is (checks.replaced_by_check_id).
     */
    public function represent(int $checkId, ?int $replacedByCheckId = null, ?string $note = null): Check
    {
        /** @var Check $check */
        $check = Check::with(['customer', 'supplier', 'branch'])->findOrFail($checkId);

        if ($check->status !== CheckStatusEnum::BOUNCED->value) {
            throw new \RuntimeException('Only a bounced check can be re-presented');
        }

        return DB::transaction(function () use ($check, $replacedByCheckId, $note) {
            $branchId = $check->branch_id;

            if ($check->direction === CheckDirectionEnum::RECEIVED->value) {
                $checksUnderCollection = Account::forCheckDirection('received', $branchId);
                $customerAccount = Account::where('model_type', User::class)
                    ->where('model_id', $check->customer_id)
                    ->where('type', AccountTypeEnum::CUSTOMER->value)
                    ->first();
                if (!$customerAccount) {
                    $customerAccount = app(AccountService::class)->createAccountForUser(User::findOrFail($check->customer_id));
                }

                $this->transactionService->create([
                    'date' => now(),
                    'description' => 'Represented Check'.($check->check_number ? ' #'.$check->check_number : ''),
                    'type' => TransactionTypeEnum::CHECK_COLLECTION->value,
                    'reference_type' => Check::class,
                    'reference_id' => $check->id,
                    'branch_id' => $branchId,
                    'note' => $note ?? $check->note ?? '',
                    'amount' => (float)$check->amount,
                    'lines' => [
                        [
                            'account_id' => $checksUnderCollection->id,
                            'type' => 'debit',
                            'amount' => (float)$check->amount,
                        ],
                        [
                            'account_id' => $customerAccount->id,
                            'type' => 'credit',
                            'amount' => (float)$check->amount,
                        ],
                    ],
                ]);

                $check->update([
                    'status' => CheckStatusEnum::UNDER_COLLECTION->value,
                    'represented_at' => now(),
                    'replaced_by_check_id' => $replacedByCheckId,
                ]);
            } else {
                $issuedChecks = Account::forCheckDirection('issued', $branchId);
                $supplierAccount = Account::where('model_type', User::class)
                    ->where('model_id', $check->supplier_id)
                    ->where('type', AccountTypeEnum::SUPPLIER->value)
                    ->first();
                if (!$supplierAccount) {
                    $supplierAccount = app(AccountService::class)->createAccountForUser(User::findOrFail($check->supplier_id));
                }

                $this->transactionService->create([
                    'date' => now(),
                    'description' => 'Represented Issued Check'.($check->check_number ? ' #'.$check->check_number : ''),
                    'type' => TransactionTypeEnum::CHECK_CLEARING->value,
                    'reference_type' => Check::class,
                    'reference_id' => $check->id,
                    'branch_id' => $branchId,
                    'note' => $note ?? $check->note ?? '',
                    'amount' => (float)$check->amount,
                    'lines' => [
                        [
                            'account_id' => $supplierAccount->id,
                            'type' => 'debit',
                            'amount' => (float)$check->amount,
                        ],
                        [
                            'account_id' => $issuedChecks->id,
                            'type' => 'credit',
                            'amount' => (float)$check->amount,
                        ],
                    ],
                ]);

                $check->update([
                    'status' => CheckStatusEnum::ISSUED->value,
                    'represented_at' => now(),
                    'replaced_by_check_id' => $replacedByCheckId,
                ]);
            }

            return $check->refresh();
        });
    }

    private function postBankCharge(?int $branchId, float $bankCharge, ?int $bankChargeAccountId, Check $check): void
    {
        $bankChargesExpense = Account::default('Bank Charges', AccountTypeEnum::BANK_CHARGES->value, $branchId);
        $bankAccount = $bankChargeAccountId
            ? Account::findOrFail($bankChargeAccountId)
            : Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $branchId);

        $this->transactionService->create([
            'date' => now(),
            'description' => 'Bank Charges for Bounced Check'.($check->check_number ? ' #'.$check->check_number : ''),
            'type' => TransactionTypeEnum::CHECK_BOUNCE->value,
            'reference_type' => Check::class,
            'reference_id' => $check->id,
            'branch_id' => $branchId,
            'note' => '',
            'amount' => $bankCharge,
            'lines' => [
                [
                    'account_id' => $bankChargesExpense->id,
                    'type' => 'debit',
                    'amount' => $bankCharge,
                ],
                [
                    'account_id' => $bankAccount->id,
                    'type' => 'credit',
                    'amount' => $bankCharge,
                ],
            ],
        ]);
    }
}
