<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\User;
use App\Repositories\AccountRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class AccountService
{
    public function __construct(private AccountRepository $repo,private PaymentMethodService $paymentMethodService) {}

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

    function getSupplierAccounts($supplierId) {
        return $this->repo->list([], [
            'model_type' => User::class,
            'model_id' => $supplierId,
            'type' => AccountTypeEnum::SUPPLIER->value,
            'active' => 1,
        ], null, 'name');
    }

    public function getBranchPaymentAccounts(?int $branchId): Collection
    {
        if (!$branchId) {
            return collect();
        }

        return Account::query()
            ->with(['paymentMethod'])
            ->where('model_type', Branch::class)
            ->where('model_id', $branchId)
            ->where('branch_id', $branchId)
            ->where('active', 1)
            ->whereNotNull('payment_method_id')
            ->orderBy('name')
            ->get();
    }

    public function getPaymentAccountsForBranchIds(array $branchIds): Collection
    {
        $branchIds = collect($branchIds)->filter()->unique()->values()->all();
        if (empty($branchIds)) {
            return collect();
        }

        return Account::query()
            ->with(['paymentMethod', 'branch'])
            ->where('model_type', Branch::class)
            ->whereIn('model_id', $branchIds)
            ->whereIn('branch_id', $branchIds)
            ->where('active', 1)
            ->whereNotNull('payment_method_id')
            ->orderBy('branch_id')
            ->orderBy('name')
            ->get();
    }

    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
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

    function delete($id) {
        $branch = $this->repo->find($id);
        if($branch) {
            return $branch->delete();
        }

        return false;
    }

    function createAccountForUser($user) {
        $paymentMethod = $this->paymentMethodService->defaultPaymentMethod();
        $type =  constant(AccountTypeEnum::class . '::' . strtoupper($user->type->value))->value;
        $data = [
            'name' => $user->name,
            'code' => Str::slug($user->id . '-' .$user->name),
            'model_type' => User::class,
            'model_id' => $user->id,
            'type' => $type,
            // 'branch_id' => branch()?->id,
            'payment_method_id' => $paymentMethod->id
        ];

        return $this->save(null,$data);
    }
}
