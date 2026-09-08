<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\ExpenseCategory;

/**
 * Shared by ExpenseService and PurchaseService, which cannot depend on each other directly
 * (ExpenseService already depends on PurchaseService for its own GL lines).
 */
class ExpenseAccountResolver
{
    static function resolve(int $branchId, ?int $expenseCategoryId = null): Account
    {
        $category = $expenseCategoryId
            ? ExpenseCategory::withTrashed()->find($expenseCategoryId)
            : null;

        $accountType = $category?->key;

        if (!$accountType instanceof AccountTypeEnum) {
            $accountType = AccountTypeEnum::EXPENSE;
        }

        return Account::default($accountType->label(), $accountType->value, $branchId);
    }
}
