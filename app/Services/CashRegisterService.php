<?php

namespace App\Services;

use App\Models\Tenant\Account;
use App\Repositories\BranchRepository;
use App\Repositories\CashRegisterRepository;

class CashRegisterService
{
    public function __construct(private CashRegisterRepository $repo) {}

    /**
     * Whether the given account is paid out of the physical cash drawer. Only cash-method
     * accounts should move the drawer counters — check, bank transfer and card payments must not.
     */
    function isCashAccount($accountId): bool
    {
        if (!$accountId) {
            return false;
        }

        $account = Account::with('paymentMethod')->find($accountId);

        return $account?->paymentMethod?->slug === 'cash';
    }

    /**
     * Sums only the cash-method portion of a payments array (each entry: account_id, amount).
     */
    function cashAmountFromPayments(array $payments): float
    {
        return collect($payments)->sum(function ($payment) {
            $amount = (float) ($payment['amount'] ?? 0);
            return $this->isCashAccount($payment['account_id'] ?? null) ? $amount : 0.0;
        });
    }

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function getOpenedCashRegister($relations = [])
    {
        $filters = [
            // 'opened_at' => now(),
            'status'=> 'open',
            'not_closed' => true,
            'admin_id' => admin()->id,
            'branch_id' => branch()?->id,
        ];

        return $this->repo->first($relations, $filters);
    }

    function save($id = null,$data) {
        if($id) {
            $branch = $this->repo->find($id);
            if($branch) {
                $branch->update($data);
                return $branch;
            }
        }

        return $this->repo->create($data);
    }

    function increment($id, $field, $amount = 1) {
        $cashRegister = $this->repo->find($id);
        if($cashRegister) {
            $cashRegister->increment($field, $amount);
            return $cashRegister;
        }

        return null;
    }

    function decrement($id, $field, $amount = 1) {
        $cashRegister = $this->repo->find($id);
        if($cashRegister) {
            $cashRegister->decrement($field, $amount);
            return $cashRegister;
        }

        return null;
    }

    function delete($id) {
        $cashRegister = $this->repo->find($id);
        if(!$cashRegister) {
            return false;
        }

        if($cashRegister->status === 'open') {
            throw new \RuntimeException(__('general.messages.cannot_delete_open_cash_register'));
        }

        if($cashRegister->transactions()->exists()) {
            throw new \RuntimeException(__('general.messages.cannot_delete_cash_register_with_transactions'));
        }

        return $cashRegister->delete(); // soft delete only — CashRegister uses SoftDeletes
    }
}
