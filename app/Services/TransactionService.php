<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Exceptions\TransactionBalanceException;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function __construct(private TransactionRepository $repo) {}

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
                ]);
            }

            return $transaction;
        });
    }

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


    function createInventoryShortageLine($data,$reverse = false) {
        $getInventoryShortageAccount = Account::default('inventory_shortage', AccountTypeEnum::INVENTORY_SHORTAGE->value,  $data['branch_id']);
        // get sub total from order products = product qty * unit cost
        $subTotal = array_sum(array_map(function($item) {
            return $item['difference'] * (float)$item['unit_cost'];
        }, $data['products']));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getInventoryShortageAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $subTotal,
        ];
    }


    function delete($id) {
        $transaction = $this->repo->find($id);
        if($transaction) {
            return $transaction->delete();
        }

        return false;
    }
}
