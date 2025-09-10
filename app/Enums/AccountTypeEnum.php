<?php

namespace App\Enums;

enum AccountTypeEnum : string
{
    case CUSTOMER = 'customer';
    case SUPPLIER = 'supplier';
    case EXPENSE = 'expense';
    case SALE_REVENUE = 'sale_revenue';
    case PURCHASE_CASH = 'purchase_cash';
    case FIXED_ASSET = 'fixed_asset';
    case CURRENT_ASSET = 'current_asset';
    case LIABILITY = 'liability';


    function label(): string
    {
        return match($this) {
            AccountTypeEnum::CUSTOMER => 'Customer', // customer transactions
            AccountTypeEnum::SUPPLIER => 'Supplier', // supplier transactions
            AccountTypeEnum::EXPENSE => 'Expense', // expense transactions
            AccountTypeEnum::SALE_REVENUE => 'Sale Revenue', // sales transactions
            AccountTypeEnum::PURCHASE_CASH => 'Purchase Cash', // purchase cash transactions
            AccountTypeEnum::FIXED_ASSET => 'Fixed Asset', // fixed asset transactions && depreciation
            AccountTypeEnum::CURRENT_ASSET => 'Current Asset', // purchase cash transactions && bank transactions
            AccountTypeEnum::LIABILITY => 'Liability', // Debts and liabilities transactions
        };
    }

    function color(): string
    {
        return match($this) {
            AccountTypeEnum::CUSTOMER => 'primary',
            AccountTypeEnum::SUPPLIER => 'warning',
            AccountTypeEnum::EXPENSE => 'danger',
            AccountTypeEnum::SALE_REVENUE => 'success',
            AccountTypeEnum::PURCHASE_CASH => 'info',
            AccountTypeEnum::FIXED_ASSET => 'info',
            AccountTypeEnum::CURRENT_ASSET => 'secondary',
            AccountTypeEnum::LIABILITY => 'dark',
        };
    }

    function toArray($except = []) {
        $data = [];
        foreach (self::cases() as $case) {
            if (!in_array($case->value, $except)) {
                $data[] = [
                    'value' => $case->value,
                    'label' => $case->label(),
                ];
            }
        }
        return $data;
    }

    function isInvalided(): bool {
        return in_array($this, [self::CUSTOMER, self::SUPPLIER]);
    }
}
