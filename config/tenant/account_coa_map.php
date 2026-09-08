<?php

// Maps accounts.type (App\Enums\AccountTypeEnum) to chart_of_accounts.code
// (see database/seeders/Tenant/ChartOfAccountsSeeder.php).
return [
    'branch_cash' => '1010',
    'customer' => '1020',
    'checks_under_collection' => '1030',
    'inventory' => '1040',
    'vat_receivable' => '1050',
    'cash_over_short' => '1060',
    'accrued_revenue' => '1070',
    'fixed_asset' => '1110',
    'accumulated_depreciation' => '1120',

    'supplier' => '2010',
    'vat_payable' => '2020',
    'issued_checks' => '2030',
    'unearned_revenue' => '2040',
    'longterm_liability' => '2100',

    'owner_account' => '3010',

    'sales' => '4010',
    'sales_discount' => '4020',
    'sales_return' => '4030',

    'cogs' => '5010',
    'purchase_discount' => '6010',
    'purchase_return' => '6070',
    'expense' => '6020',
    'finance_expense' => '6020',
    'marketing_expense' => '6020',
    'operating_expense' => '6020',
    'general_and_administrative_expense' => '6020',
    'maintenance_and_depreciation_expense' => '6020',
    'inventory_expense' => '6020',
    'inventory_shortage' => '6030',
    'depreciation_expense' => '6040',
    'purchase_price_variance' => '6050',
    'gain_loss_on_disposal' => '6060',
];
