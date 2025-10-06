<?php

namespace App\Enums;

enum AccountTypeEnum : string
{
    case BRANCH_CASH = 'branch_cash';
    case OWNER_ACCOUNT = 'owner_account';
    case CUSTOMER = 'customer';
    case SUPPLIER = 'supplier';
    case EXPENSE = 'expense';
    case SALES = 'sales'; // revenue from sales -> sell price for product stock
    case INVENTORY = 'inventory'; // inventory purchases
    case COGS = 'cogs'; // cost of goods sold
    case INVENTORY_SHORTAGE = 'inventory_shortage'; // inventory loss adjustments
    case FIXED_ASSET = 'fixed_asset'; // fixed asset transactions && depreciation
    case CURRENT_ASSET = 'current_asset'; // sales credits , bank transactions
    // case CURRENT_LIABILITY = 'current_liability'; // purchase credits , expenses due within a year
    case LONGTERM_LIABILITY = 'longterm_liability'; // Long-term debts and liabilities
    case VAT_PAYABLE = 'vat_payable'; // on sales
    case VAT_RECEIVABLE = 'vat_receivable'; // VAT on purchases
    case SALES_DISCOUNT = 'sales_discount';
    case PURCHASE_DISCOUNT = 'purchase_discount';
    case SALES_RETURN = 'sales_return';
    case PURCHASE_RETURN = 'purchase_return';


    function label(): string
    {
        return match($this) {
            AccountTypeEnum::CUSTOMER => 'Customer', // customer transactions
            AccountTypeEnum::SUPPLIER => 'Supplier', // supplier transactions
            AccountTypeEnum::EXPENSE => 'Expense', // expense transactions
            AccountTypeEnum::FIXED_ASSET => 'Fixed Asset', // fixed asset transactions && depreciation
            AccountTypeEnum::CURRENT_ASSET => 'Current Asset', // purchase cash transactions && bank transactions
            // AccountTypeEnum::CURRENT_LIABILITY => 'Current Liability', // Current liabilities transactions
            AccountTypeEnum::LONGTERM_LIABILITY => 'Long-term Liability', // Long-term liabilities transactions
            AccountTypeEnum::BRANCH_CASH => 'Branch Cash',
            AccountTypeEnum::OWNER_ACCOUNT => 'Owner Account',
            AccountTypeEnum::SALES => 'Sales',
            AccountTypeEnum::INVENTORY => 'Inventory',
            AccountTypeEnum::COGS => 'Cost of Goods Sold',
            AccountTypeEnum::INVENTORY_SHORTAGE => 'Inventory Shortage',
            AccountTypeEnum::VAT_PAYABLE => 'VAT Payable',
            AccountTypeEnum::VAT_RECEIVABLE => 'VAT Receivable',
            AccountTypeEnum::SALES_DISCOUNT => 'Sales Discount',
            AccountTypeEnum::PURCHASE_DISCOUNT => 'Purchase Discount',
            AccountTypeEnum::SALES_RETURN => 'Sales Return',
            AccountTypeEnum::PURCHASE_RETURN => 'Purchase Return',
        };
    }

    function color(): string
    {
        return match($this) {
            AccountTypeEnum::CUSTOMER => 'primary',
            AccountTypeEnum::SUPPLIER => 'warning',
            AccountTypeEnum::EXPENSE => 'danger',
            AccountTypeEnum::FIXED_ASSET => 'info',
            AccountTypeEnum::CURRENT_ASSET => 'secondary',
            // AccountTypeEnum::CURRENT_LIABILITY => 'dark',
            AccountTypeEnum::LONGTERM_LIABILITY => 'dark',
            AccountTypeEnum::BRANCH_CASH => 'success',
            AccountTypeEnum::OWNER_ACCOUNT => 'success',
            AccountTypeEnum::SALES => 'success',
            AccountTypeEnum::INVENTORY => 'info',
            AccountTypeEnum::COGS => 'warning',
            AccountTypeEnum::INVENTORY_SHORTAGE => 'danger',
            AccountTypeEnum::VAT_PAYABLE => 'secondary',
            AccountTypeEnum::VAT_RECEIVABLE => 'secondary',
            AccountTypeEnum::SALES_DISCOUNT => 'info',
            AccountTypeEnum::PURCHASE_DISCOUNT => 'info',
            AccountTypeEnum::SALES_RETURN => 'warning',
            AccountTypeEnum::PURCHASE_RETURN => 'warning',
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
