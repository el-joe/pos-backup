<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Exceptions\TransactionBalanceException;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Repositories\TransactionRepository;
use App\Services\LedgerBridgeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function __construct(private TransactionRepository $repo, private LedgerBridgeService $ledgerBridge) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function activeList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter + [
            'active' => 1
        ], $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function create($data) {
        $type = $data['type'] ?? null;
        $referenceId = $data['reference_id'] ?? null;
        $rawLines = $data['lines'] ?? [];

        if (empty($rawLines)) {
            throw TransactionBalanceException::emptyLines($type, $referenceId);
        }

        foreach ($rawLines as $line) {
            if (!is_array($line) || empty($line['account_id']) || !is_numeric($line['amount'] ?? null)) {
                throw TransactionBalanceException::invalidLine($type, $referenceId);
            }
        }

        // zero-amount lines carry no accounting effect and are dropped before balancing/persisting
        $lines = array_values(array_filter($rawLines, fn($line) => (float) $line['amount'] != 0));

        if (empty($lines)) {
            throw TransactionBalanceException::emptyLines($type, $referenceId);
        }

        $debitTotal = 0.0;
        $creditTotal = 0.0;

        foreach ($lines as $line) {
            $amount = (float) $line['amount'];
            if (($line['type'] ?? 'debit') === 'credit') {
                $creditTotal += $amount;
            } else {
                $debitTotal += $amount;
            }
        }

        if (abs($debitTotal - $creditTotal) > 0.005) {
            throw TransactionBalanceException::unbalanced($type, $referenceId, $debitTotal, $creditTotal);
        }

        $payloadAmount = $data['amount'] ?? null;
        if ($payloadAmount !== null && abs((float) $payloadAmount - $debitTotal) > 0.005) {
            Log::warning('TransactionService::create amount mismatch — using balanced debit total instead of caller-supplied amount', [
                'type' => $type,
                'reference_id' => $referenceId,
                'payload_amount' => $payloadAmount,
                'debit_total' => $debitTotal,
            ]);
        }

        return DB::transaction(function () use ($data, $lines, $debitTotal, $type, $referenceId) {
            $transaction = $this->repo->create([
                'date' => $data['date'] ?? now(),
                'description' => $data['description'] ?? $data['note'] ?? '',
                'type' => $type,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $referenceId,
                'branch_id' => $data['branch_id'] ?? null,
                'note' => $data['note'] ?? '',
                'amount' => $debitTotal,
            ]);

            foreach ($lines as $line) {
                $transaction->lines()->create([
                    'account_id' => $line['account_id'],
                    'type' => $line['type'] ?? 'debit',
                    'amount' => $line['amount'],
                    'cost_center_id' => $line['cost_center_id'] ?? null,
                    'project_id' => $line['project_id'] ?? null,
                ]);
            }

            $this->ledgerBridge->post($transaction);

            return $transaction;
        });
    }

    /**
     * @deprecated Opening/closing a cash register shift is an operational control event, not a
     * capital movement — the float already sits in Branch Cash, so counting it must not post to
     * the ledger (see CashRegisterPage::openRegister/closeRegister). This method is no longer
     * called from the shift open/close flow. It is kept only as the building block for a genuine
     * owner capital injection/drawing action, which does not yet have a UI — do not wire it back
     * into cash register open/close.
     */
    function createOpenBalanceTransaction($data,$reverse = false) {
        $ownerAccount = Account::default('owner_account', AccountTypeEnum::OWNER_ACCOUNT->value);
        $branchCashAccount = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $data['branch_id']);

        $transaction = $this->create([
            'date' => $data['date'] ?? now(),
            'description' => $reverse ? 'Closing Balance' : 'Opening Balance',
            'type' => $reverse ? TransactionTypeEnum::CLOSING_BALANCE->value : TransactionTypeEnum::OPENING_BALANCE->value,
            'branch_id' => $data['branch_id'] ?? null,
            'amount' => $data['amount'] ?? 0,
            'reference_type' => Branch::class,
            'reference_id' => $data['branch_id'] ?? null,
            'lines' => [
                [
                    'account_id' => $branchCashAccount->id,
                    'type' => $reverse ? 'credit' : 'debit',
                    'amount' => $data['amount'] ?? 0,
                ],
                [
                    'account_id' => $ownerAccount->id,
                    'type' => $reverse ? 'debit' : 'credit',
                    'amount' => $data['amount'] ?? 0,
                ],
            ],
        ]);

        return $transaction;
    }

    function createCashDepositTransaction($data) {
        $ownerAccount = Account::default('owner_account', AccountTypeEnum::OWNER_ACCOUNT->value);
        $branchCashAccount = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $data['branch_id']);

        return $this->create([
            'date' => $data['date'] ?? now(),
            'description' => $data['description'] ?? 'Cash Deposit',
            'type' => TransactionTypeEnum::CASH_DEPOSIT->value,
            'branch_id' => $data['branch_id'] ?? null,
            'note' => $data['note'] ?? '',
            'amount' => $data['amount'] ?? 0,
            'reference_type' => Branch::class,
            'reference_id' => $data['branch_id'] ?? null,
            'lines' => [
                [
                    'account_id' => $branchCashAccount->id,
                    'type' => 'debit',
                    'amount' => $data['amount'] ?? 0,
                ],
                [
                    'account_id' => $ownerAccount->id,
                    'type' => 'credit',
                    'amount' => $data['amount'] ?? 0,
                ],
            ],
        ]);
    }

    function createCashWithdrawalTransaction($data) {
        $ownerAccount = Account::default('owner_account', AccountTypeEnum::OWNER_ACCOUNT->value);
        $branchCashAccount = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $data['branch_id']);

        return $this->create([
            'date' => $data['date'] ?? now(),
            'description' => $data['description'] ?? 'Cash Withdrawal',
            'type' => TransactionTypeEnum::CASH_WITHDRAWAL->value,
            'branch_id' => $data['branch_id'] ?? null,
            'note' => $data['note'] ?? '',
            'amount' => $data['amount'] ?? 0,
            'reference_type' => Branch::class,
            'reference_id' => $data['branch_id'] ?? null,
            'lines' => [
                [
                    'account_id' => $branchCashAccount->id,
                    'type' => 'credit',
                    'amount' => $data['amount'] ?? 0,
                ],
                [
                    'account_id' => $ownerAccount->id,
                    'type' => 'debit',
                    'amount' => $data['amount'] ?? 0,
                ],
            ],
        ]);
    }


    /**
     * Posts the cash variance found when closing a register. $data['amount'] is the signed
     * discrepancy (admin-counted closing balance minus the calculated closing balance):
     * negative = shortage (DR Cash Over/Short expense, CR Branch Cash),
     * positive = overage (DR Branch Cash, CR Cash Over/Short).
     * Callers must skip calling this when the discrepancy is ~0 — create() rejects empty lines.
     */
    function createCashOverShortTransaction($data) {
        $cashOverShortAccount = Account::default('Cash Over/Short', AccountTypeEnum::CASH_OVER_SHORT->value, $data['branch_id'] ?? null);
        $branchCashAccount = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $data['branch_id'] ?? null);

        $discrepancy = (float) ($data['amount'] ?? 0);
        $amount = abs($discrepancy);
        $isShortage = $discrepancy < 0;

        return $this->create([
            'date' => $data['date'] ?? now(),
            'description' => $data['description'] ?? ($isShortage ? 'Cash Register Shortage' : 'Cash Register Overage'),
            'type' => TransactionTypeEnum::CASH_OVER_SHORT->value,
            'branch_id' => $data['branch_id'] ?? null,
            'note' => $data['note'] ?? '',
            'amount' => $amount,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'lines' => $isShortage ? [
                [
                    'account_id' => $cashOverShortAccount->id,
                    'type' => 'debit',
                    'amount' => $amount,
                ],
                [
                    'account_id' => $branchCashAccount->id,
                    'type' => 'credit',
                    'amount' => $amount,
                ],
            ] : [
                [
                    'account_id' => $branchCashAccount->id,
                    'type' => 'debit',
                    'amount' => $amount,
                ],
                [
                    'account_id' => $cashOverShortAccount->id,
                    'type' => 'credit',
                    'amount' => $amount,
                ],
            ],
        ]);
    }

    function createInventoryShortageLine($data,$reverse = false) {
        $getInventoryShortageAccount = Account::default('inventory_shortage', AccountTypeEnum::INVENTORY_SHORTAGE->value,  $data['branch_id']);

        return [
            'account_id' => $getInventoryShortageAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $this->stockAdjustmentValue($data['products']),
        ];
    }

    function createInventoryGainLine($data,$reverse = false) {
        $getInventoryGainAccount = Account::default('inventory_gain', AccountTypeEnum::INVENTORY_GAIN->value, $data['branch_id']);

        return [
            'account_id' => $getInventoryGainAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $this->stockAdjustmentValue($data['products']),
        ];
    }

    /**
     * The Inventory-account side of a stock-taking adjustment. $type is the debit/credit
     * side for the non-reversed posting (credit for a shortage issue, debit for a gain
     * receipt) — the caller flips it when reversing.
     */
    function createInventoryAdjustmentLine($data, $type) {
        $getInventoryAccount = Account::default('Inventory', AccountTypeEnum::INVENTORY->value, $data['branch_id']);

        return [
            'account_id' => $getInventoryAccount->id,
            'type' => $type,
            'amount' => $this->stockAdjustmentValue($data['products']),
        ];
    }

    /**
     * Sum of |difference * unit_cost| across stock-taking product rows. Always positive —
     * direction (debit/credit) is decided by the caller, not the sign of the count variance.
     */
    private function stockAdjustmentValue($products): float {
        return abs(array_sum(array_map(function($item) {
            return (float) $item['difference'] * (float) $item['unit_cost'];
        }, $products)));
    }


    /**
     * Creates a mirrored contra-entry for $t (every line's debit/credit flipped, same amounts)
     * and links it back via reversed_by_transaction_id. Never deletes or mutates $t's own lines —
     * both the original and the reversal remain in the ledger for audit purposes.
     */
    function reverse(\App\Models\Tenant\Transaction $t, string $reason): \App\Models\Tenant\Transaction
    {
        if ($t->reversed_by_transaction_id) {
            return $t->refresh()->reversal;
        }

        $lines = $t->lines()->get()->map(fn ($line) => [
            'account_id' => $line->account_id,
            'type' => $line->type === 'debit' ? 'credit' : 'debit',
            'amount' => $line->amount,
        ])->all();

        return DB::transaction(function () use ($t, $lines, $reason) {
            $reversal = $this->create([
                'date' => now(),
                'description' => 'Reversal of Transaction #' . $t->id . ': ' . $t->description,
                'type' => $t->type instanceof \BackedEnum ? $t->type->value : $t->type,
                'reference_type' => $t->reference_type,
                'reference_id' => $t->reference_id,
                'branch_id' => $t->branch_id,
                'note' => $reason,
                'lines' => $lines,
            ]);

            $t->update([
                'reversed_by_transaction_id' => $reversal->id,
                'reversal_reason' => $reason,
            ]);

            $this->ledgerBridge->reverse($t->refresh());

            return $reversal;
        });
    }

    function delete($id) {
        $transaction = $this->repo->find($id);
        if($transaction) {
            return $transaction->delete();
        }

        return false;
    }
}
