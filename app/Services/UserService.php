<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(private UserRepository $repo,private AccountService $accountService) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function suppliersList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        $filter = array_merge($filter, [
            'type' => 'supplier',
            'active' => true,
        ]);

        return $this->repo->list($relations, $filter , $perPage, $orderByDesc);
    }

    function customersList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        $filter = array_merge($filter, [
            'type' => 'customer',
            'active' => true,
        ]);

        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function findOrFail($id = null, $relations = [])
    {
        return $this->repo->findOrFail($id, $relations);
    }


    function save($id = null,$data) {
        if($id) {
            $user = $this->repo->find($id);
            if($user) {
                $user->update($data);
            }
        }else{
            $user = $this->repo->create($data);
        }

        if($user && !$id) {
            $this->accountService->createAccountForUser($user);
        }

        return $user;
    }

    function delete($id) {
        $user = $this->repo->find($id);
        if($user) {
            return $user->delete();
        }

        return false;
    }

    function checkIfUserExistsIntoSameType(string $data,string $type) {
        $userEmail = $this->repo->first(filter : ['email' => $data, 'type' => $type , 'is_deleted' => false]);
        $userPhone = $this->repo->first(filter : ['phone' => $data , 'type' => $type , 'is_deleted' => false]);
        return $userEmail !== null || $userPhone !== null;
    }

    function customerDueAmountsReport($fromDate, $toDate,$customerId = null,$addSelect = [])
    {
        $totalDebit = "SUM(CASE WHEN transaction_lines.type = 'debit' THEN transaction_lines.amount ELSE 0 END)";
        $totalCredit = "SUM(CASE WHEN transaction_lines.type = 'credit' THEN transaction_lines.amount ELSE 0 END)";

        return DB::table('transaction_lines')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->join('users', function($join) {
                $join->on('users.id', '=', 'accounts.model_id')
                     ->where('accounts.model_type', User::class)
                     ->where('users.type','customer');
            })
            ->select(
                'accounts.id as account_id',
                DB::raw("CONCAT(users.name, ' (', accounts.code, ')') as customer_name"),
                DB::raw("$totalDebit as total_debit"),
                DB::raw("$totalCredit as total_credit"),
                DB::raw("($totalDebit - $totalCredit) as balance")
            )
            ->when(count($addSelect) > 0, function ($query) use ($addSelect) {
                return $query->addSelect($addSelect);
            })
            ->where('accounts.type', AccountTypeEnum::CUSTOMER)
            ->whereBetween('transaction_lines.created_at', [$fromDate, $toDate])
            ->when($customerId, function ($query, $customerId) {
                return $query->where('users.id', $customerId);
            })
            ->groupBy('accounts.id', 'users.name')
            ->havingRaw('balance > 0') // Only customers who owe you money
            ->orderByDesc('balance')
            ->get();
    }

    function supplierDueAmountsReport($fromDate, $toDate,$supplierId = null,$addSelect = [])
    {
        $totalDebit = "SUM(CASE WHEN transaction_lines.type = 'debit' THEN transaction_lines.amount ELSE 0 END)";
        $totalCredit = "SUM(CASE WHEN transaction_lines.type = 'credit' THEN transaction_lines.amount ELSE 0 END)";

        return DB::table('transaction_lines')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->join('users', function($join) {
                $join->on('users.id', '=', 'accounts.model_id')
                     ->where('accounts.model_type', User::class)
                     ->where('users.type','supplier');
            })
            ->select(
                'accounts.id as account_id',
                DB::raw("CONCAT(users.name, ' (', accounts.code, ')') as supplier_name"),
                DB::raw("$totalDebit as total_debit"),
                DB::raw("$totalCredit as total_credit"),
                DB::raw("($totalCredit - $totalDebit) as balance")
            )
            ->when(count($addSelect) > 0, function ($query) use ($addSelect) {
                return $query->addSelect($addSelect);
            })
            ->where('accounts.type', AccountTypeEnum::SUPPLIER)
            ->whereBetween('transaction_lines.created_at', [$fromDate, $toDate])
            ->when($supplierId, function ($query, $supplierId) {
                return $query->where('users.id', $supplierId);
            })
            ->groupBy('accounts.id', 'users.name')
            ->havingRaw('balance > 0') // Only suppliers you owe money to
            ->orderByDesc('balance')
            ->get();
    }
}
