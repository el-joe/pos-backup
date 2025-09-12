<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\User;
use App\Repositories\AccountRepository;
use Str;

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
        $data = [
            'name' => $user->name,
            'code' => Str::slug($user->id . '-' .$user->name),
            'model_type' => User::class,
            'model_id' => $user->id,
            'type' => AccountTypeEnum::{strtoupper($user->type->value)}->value,
            // 'branch_id' => $user->branch_id,
            'payment_method_id' => $paymentMethod->id
        ];

        return $this->save(null,$data);
    }
}
