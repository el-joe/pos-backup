<?php

// Maps accounts.type (App\Enums\AccountTypeEnum) to chart_of_accounts.code
// (see database/seeders/Tenant/ChartOfAccountsSeeder.php).
return [
    'branch_cash' => '1010',
    'customer' => '1020',
    'checks_under_collection' => '1030',
    'inventory' => '1040',
    'vat_receivable' => '1050',
    'fixed_asset' => '1110',

    'supplier' => '2010',
    'vat_payable' => '2020',
    'issued_checks' => '2030',
    'longterm_liability' => '2100',

    'owner_account' => '3010',

    'sales' => '4010',
    'sales_discount' => '4020',

    'cogs' => '5010',
    'purchase_discount' => '6010',
    'expense' => '6020',
    'finance_expense' => '6020',
    'marketing_expense' => '6020',
    'operating_expense' => '6020',
    'general_and_administrative_expense' => '6020',
    'maintenance_and_depreciation_expense' => '6020',
    'inventory_expense' => '6020',
    'inventory_shortage' => '6030',
];
