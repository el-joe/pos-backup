<?php

namespace App\Enums;

enum AccountTypeEnum : string
{
    case BRANCH_CASH = 'branch_cash';
    case OWNER_ACCOUNT = 'owner_account';
    case CUSTOMER = 'customer';
    case SUPPLIER = 'supplier';
    // Expenses accounts
    case EXPENSE = 'expense';
    case FINANCE_EXPENSE = 'finance_expense';
    case MARKETING_EXPENSE = 'marketing_expense';
    case OPERATING_EXPENSE = 'operating_expense';
    case GENERAL_AND_ADMINISTRATIVE_EXPENSE = 'general_and_administrative_expense';
    case MAINTENANCE_AND_DEPRECIATION_EXPENSE = 'maintenance_and_depreciation_expense';
    case INVENTORY_EXPENSE = 'inventory_expense'; // NON-COGS

    // --------------------------------------------------------------------------------
    case SALES = 'sales'; // revenue from sales -> sell price for product stock
    case INVENTORY = 'inventory'; // inventory purchases
    case COGS = 'cogs'; // cost of goods sold
    case INVENTORY_SHORTAGE = 'inventory_shortage'; // inventory loss adjustments
    case FIXED_ASSET = 'fixed_asset'; // fixed asset transactions && depreciation
    // case CURRENT_ASSET = 'current_asset'; // sales credits , bank transactions
    // case CURRENT_LIABILITY = 'current_liability'; // purchase credits , expenses due within a year
    case LONGTERM_LIABILITY = 'longterm_liability'; // Long-term debts and liabilities
    case VAT_PAYABLE = 'vat_payable'; // on sales
    case VAT_RECEIVABLE = 'vat_receivable'; // VAT on purchases
    case SALES_DISCOUNT = 'sales_discount';
    case PURCHASE_DISCOUNT = 'purchase_discount';
    case SALES_RETURN = 'sales_return';
    case PURCHASE_RETURN = 'purchase_return';

    case UNEARNED_REVENUE = 'unearned_revenue'; // advance payments from customers---- new
    case ACCRUED_REVENUE = 'accrued_revenue'; // revenue earned but not yet received---- new


    function label(): string
    {
        return match($this) {
            AccountTypeEnum::CUSTOMER => 'Customer', // customer transactions
            AccountTypeEnum::SUPPLIER => 'Supplier', // supplier transactions
            AccountTypeEnum::EXPENSE => 'Expense', // expense transactions
            AccountTypeEnum::FINANCE_EXPENSE => 'Finance Expense',
            AccountTypeEnum::MARKETING_EXPENSE => 'Marketing Expense',
            AccountTypeEnum::OPERATING_EXPENSE => 'Operating Expense',
            AccountTypeEnum::GENERAL_AND_ADMINISTRATIVE_EXPENSE => 'General & Administrative Expense',
            AccountTypeEnum::MAINTENANCE_AND_DEPRECIATION_EXPENSE => 'Maintenance & Depreciation Expense',
            AccountTypeEnum::INVENTORY_EXPENSE => 'Inventory Expense',
            AccountTypeEnum::FIXED_ASSET => 'Fixed Asset', // fixed asset transactions && depreciation
            // AccountTypeEnum::CURRENT_ASSET => 'Current Asset', // purchase cash transactions && bank transactions
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
            AccountTypeEnum::FINANCE_EXPENSE => 'danger',
            AccountTypeEnum::MARKETING_EXPENSE => 'danger',
            AccountTypeEnum::OPERATING_EXPENSE => 'danger',
            AccountTypeEnum::GENERAL_AND_ADMINISTRATIVE_EXPENSE => 'danger',
            AccountTypeEnum::MAINTENANCE_AND_DEPRECIATION_EXPENSE => 'danger',
            AccountTypeEnum::INVENTORY_EXPENSE => 'danger',
            AccountTypeEnum::FIXED_ASSET => 'info',
            // AccountTypeEnum::CURRENT_ASSET => 'secondary',
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

    function expensesAccountsTranslation(){
        return match($this) {
            self::FINANCE_EXPENSE => 'مصروفات تمويلية',
            self::MARKETING_EXPENSE => 'مصروفات تسويقية',
            self::OPERATING_EXPENSE => 'مصروفات تشغيلية',
            self::GENERAL_AND_ADMINISTRATIVE_EXPENSE => 'مصروفات عامة وإدارية',
            self::MAINTENANCE_AND_DEPRECIATION_EXPENSE => 'مصروفات صيانة واستهلاك',
            self::INVENTORY_EXPENSE => 'مصروفات المخزون (غير تكلفة بضاعة)',
        };
    }

    static function defaultExpensesAccounts(): array {
        return [
            self::FINANCE_EXPENSE->value => [
                'en' => [
                    'Loan Interest Expense',
                    'Bank Facility Interest',
                    'Bank Charges & Commissions',
                    'Letter of Credit Fees',
                    'Bank Guarantee Fees',
                    'Exchange Rate Loss',
                    'Payment Gateway Fees',
                ],
                'ar' => [
                    'مصروف فوائد القروض',
                    'فوائد التسهيلات البنكية',
                    'رسوم وعمولات بنكية',
                    'رسوم الاعتماد المستندي',
                    'رسوم خطاب الضمان',
                    'خسائر فروق العملة',
                    'رسوم بوابة الدفع',
                ],
            ],
            self::MARKETING_EXPENSE->value => [
                'en' => [
                    'Advertising Expense',
                    'Digital Marketing',
                    'Facebook Ads',
                    'Google Ads',
                    'TikTok Ads',
                    'Promotional Materials',
                    'Printing & Brochures',
                    'Marketing Campaigns',
                    'Sales Promotions',
                    'Sales Commissions',
                ],
                'ar' => [
                    'مصروفات إعلان',
                    'التسويق الرقمي',
                    'إعلانات فيسبوك',
                    'إعلانات جوجل',
                    'إعلانات تيك توك',
                    'مواد ترويجية',
                    'طباعة وبروشورات',
                    'حملات تسويقية',
                    'عروض ترويجية للمبيعات',
                    'عمولات المبيعات',
                ],
            ],
            self::OPERATING_EXPENSE->value => [
                'en' => [
                    'Rent Expense',
                    'Electricity Expense',
                    'Water Expense',
                    'Gas Expense',
                    'Internet & Communication',
                    'Salaries & Wages',
                    'Bonuses & Incentives',
                    'Social Insurance',
                    'Equipment Operating Expense',
                    'Spare Parts',
                    'Fuel Expense',
                    'Transportation Expense',
                    'Operating Supplies',
                    'Cleaning Supplies',
                    'Daily Operating Expenses',
                ],
                'ar' => [
                    'مصروف إيجار',
                    'مصروف كهرباء',
                    'مصروف مياه',
                    'مصروف غاز',
                    'مصروفات الإنترنت والاتصالات',
                    'الرواتب والأجور',
                    'مكافآت وحوافز',
                    'التأمينات الاجتماعية',
                    'مصروف تشغيل المعدات',
                    'قطع غيار',
                    'مصروف وقود',
                    'مصروف نقل ومواصلات',
                    'مستلزمات التشغيل',
                    'مستلزمات النظافة',
                    'مصروفات تشغيل يومية',
                ],
            ],
            self::GENERAL_AND_ADMINISTRATIVE_EXPENSE->value => [
                'en' => [
                    'Administrative Salaries',
                    'Accounting & Audit Fees',
                    'Legal Fees',
                    'Consulting Fees',
                    'Software Subscriptions',
                    'Office Expenses',
                    'Stationery',
                    'Licenses & Renewals',
                    'Government Fees',
                    'Fines & Penalties',
                    'Donations',
                    'Miscellaneous Expenses',
                ],
                'ar' => [
                    'رواتب إدارية',
                    'أتعاب محاسبة ومراجعة',
                    'أتعاب قانونية',
                    'أتعاب استشارات',
                    'اشتراكات البرامج',
                    'مصروفات مكتبية',
                    'قرطاسية',
                    'تراخيص وتجديدات',
                    'رسوم حكومية',
                    'غرامات وجزاءات',
                    'تبرعات',
                    'مصروفات متنوعة',
                ],
            ],
            self::MAINTENANCE_AND_DEPRECIATION_EXPENSE->value => [
                'en' => [
                    'Building Maintenance',
                    'Vehicle Maintenance',
                    'Equipment Maintenance',
                    'System Maintenance',
                    'Depreciation – Buildings',
                    'Depreciation – Vehicles',
                    'Depreciation – Equipment',
                    'Depreciation – Furniture',
                ],
                'ar' => [
                    'صيانة المباني',
                    'صيانة المركبات',
                    'صيانة المعدات',
                    'صيانة الأنظمة',
                    'إهلاك - مبانٍ',
                    'إهلاك - مركبات',
                    'إهلاك - معدات',
                    'إهلاك - أثاث',
                ],
            ],
            self::INVENTORY_EXPENSE->value => [
                'en' => [
                    'Inventory Damage Expense',
                    'Inventory Shortage Expense',
                    'Storage & Warehouse Expense',
                    'Internal Inventory Transportation',
                ],
                'ar' => [
                    'مصروف تلف المخزون',
                    'مصروف عجز المخزون',
                    'مصروفات التخزين والمستودعات',
                    'نقل داخلي للمخزون',
                ],
            ],
        ];
    }
}
