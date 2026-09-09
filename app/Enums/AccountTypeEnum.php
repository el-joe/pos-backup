<?php

namespace App\Enums;

enum AccountTypeEnum : string
{
    case BRANCH_CASH = 'branch_cash';
    case OWNER_ACCOUNT = 'owner_account';
    case CUSTOMER = 'customer';
    case SUPPLIER = 'supplier';
    case CHECKS_UNDER_COLLECTION = 'checks_under_collection'; // current assets
    case ISSUED_CHECKS = 'issued_checks'; // current liabilities
    case CASH_OVER_SHORT = 'cash_over_short'; // cash register variance at close

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
    case ACCUMULATED_DEPRECIATION = 'accumulated_depreciation'; // contra-asset
    case DEPRECIATION_EXPENSE = 'depreciation_expense';
    case GAIN_LOSS_ON_DISPOSAL = 'gain_loss_on_disposal'; // fixed asset disposal gain/loss
    // case CURRENT_ASSET = 'current_asset'; // sales credits , bank transactions
    // case CURRENT_LIABILITY = 'current_liability'; // purchase credits , expenses due within a year
    case LONGTERM_LIABILITY = 'longterm_liability'; // Long-term debts and liabilities
    case VAT_PAYABLE = 'vat_payable'; // on sales
    case VAT_RECEIVABLE = 'vat_receivable'; // VAT on purchases
    case SALES_DISCOUNT = 'sales_discount';
    case PURCHASE_DISCOUNT = 'purchase_discount';
    case SALES_RETURN = 'sales_return';
    case PURCHASE_RETURN = 'purchase_return';
    case PURCHASE_PRICE_VARIANCE = 'purchase_price_variance'; // WAC vs original invoice price on a purchase refund

    case UNEARNED_REVENUE = 'unearned_revenue'; // advance payments from customers---- new
    case ACCRUED_REVENUE = 'accrued_revenue'; // revenue earned but not yet received---- new
    case PREPAID_ASSET = 'prepaid_asset'; // prepaid expenses recognized as a current asset until amortized ---- prompt 09
    case ACCRUED_EXPENSES = 'accrued_expenses'; // liability for expenses recognized before cash settlement ---- prompt 13

    // Payroll accounts ---- new
    case SALARIES_EXPENSE = 'salaries_expense'; // gross payroll cost
    case EMPLOYER_CONTRIBUTIONS_EXPENSE = 'employer_contributions_expense'; // employer-side statutory contributions
    case SALARIES_PAYABLE = 'salaries_payable'; // net pay owed to employees until paid out
    case SOCIAL_INSURANCE_PAYABLE = 'social_insurance_payable'; // employee + employer social insurance owed to authority
    case INCOME_TAX_PAYABLE = 'income_tax_payable'; // withheld income tax owed to authority


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
            AccountTypeEnum::ACCUMULATED_DEPRECIATION => 'Accumulated Depreciation',
            AccountTypeEnum::DEPRECIATION_EXPENSE => 'Depreciation Expense',
            AccountTypeEnum::GAIN_LOSS_ON_DISPOSAL => 'Gain/Loss on Disposal',
            // AccountTypeEnum::CURRENT_ASSET => 'Current Asset', // purchase cash transactions && bank transactions
            // AccountTypeEnum::CURRENT_LIABILITY => 'Current Liability', // Current liabilities transactions
            AccountTypeEnum::LONGTERM_LIABILITY => 'Long-term Liability', // Long-term liabilities transactions
            AccountTypeEnum::BRANCH_CASH => 'Branch Cash',
            AccountTypeEnum::OWNER_ACCOUNT => 'Owner Account',
            AccountTypeEnum::CHECKS_UNDER_COLLECTION => 'Checks Under Collection',
            AccountTypeEnum::ISSUED_CHECKS => 'Issued Checks',
            AccountTypeEnum::CASH_OVER_SHORT => 'Cash Over/Short',
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
            AccountTypeEnum::PURCHASE_PRICE_VARIANCE => 'Purchase Price Variance',
            self::UNEARNED_REVENUE => 'Unearned Revenue',
            self::ACCRUED_REVENUE => 'Accrued Revenue',
            self::PREPAID_ASSET => 'Prepaid Asset',
            self::ACCRUED_EXPENSES => 'Accrued Expenses',
            self::SALARIES_EXPENSE => 'Salaries Expense',
            self::EMPLOYER_CONTRIBUTIONS_EXPENSE => 'Employer Contributions Expense',
            self::SALARIES_PAYABLE => 'Salaries Payable',
            self::SOCIAL_INSURANCE_PAYABLE => 'Social Insurance Payable',
            self::INCOME_TAX_PAYABLE => 'Income Tax Payable',
        };
    }

    function translatedLabel(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale !== 'ar') {
            return $this->label();
        }

        return match ($this) {
            self::BRANCH_CASH => 'نقدية الفرع',
            self::OWNER_ACCOUNT => 'حساب المالك',
            self::CUSTOMER => 'ذمم العملاء',
            self::SUPPLIER => 'ذمم الموردين',
            self::CHECKS_UNDER_COLLECTION => 'شيكات تحت التحصيل',
            self::ISSUED_CHECKS => 'شيكات صادرة',
            self::CASH_OVER_SHORT => 'عجز/زيادة الخزينة',

            self::EXPENSE => 'مصروفات',
            self::FINANCE_EXPENSE => 'مصروفات تمويلية',
            self::MARKETING_EXPENSE => 'مصروفات تسويقية',
            self::OPERATING_EXPENSE => 'مصروفات تشغيلية',
            self::GENERAL_AND_ADMINISTRATIVE_EXPENSE => 'مصروفات عامة وإدارية',
            self::MAINTENANCE_AND_DEPRECIATION_EXPENSE => 'مصروفات صيانة واستهلاك',
            self::INVENTORY_EXPENSE => 'مصروفات المخزون (غير تكلفة بضاعة)',

            self::SALES => 'المبيعات',
            self::INVENTORY => 'المخزون',
            self::COGS => 'تكلفة البضاعة المباعة',
            self::INVENTORY_SHORTAGE => 'عجز المخزون',
            self::FIXED_ASSET => 'الأصول الثابتة',
            self::ACCUMULATED_DEPRECIATION => 'مجمع الإهلاك',
            self::DEPRECIATION_EXPENSE => 'مصروف الإهلاك',
            self::GAIN_LOSS_ON_DISPOSAL => 'أرباح/خسائر التخلص من الأصول',
            self::LONGTERM_LIABILITY => 'التزامات طويلة الأجل',
            self::VAT_PAYABLE => 'ضريبة القيمة المضافة المستحقة',
            self::VAT_RECEIVABLE => 'ضريبة القيمة المضافة القابلة للاسترداد',
            self::SALES_DISCOUNT => 'خصم المبيعات',
            self::PURCHASE_DISCOUNT => 'خصم المشتريات',
            self::SALES_RETURN => 'مرتجعات المبيعات',
            self::PURCHASE_RETURN => 'مرتجعات المشتريات',
            self::PURCHASE_PRICE_VARIANCE => 'فرق سعر الشراء',

            self::UNEARNED_REVENUE => 'إيرادات مقدمة',
            self::ACCRUED_REVENUE => 'إيرادات مستحقة',
            self::PREPAID_ASSET => 'مصروفات مقدمة (أصل)',
            self::ACCRUED_EXPENSES => 'مصروفات مستحقة',
            self::SALARIES_EXPENSE => 'مصروف الرواتب',
            self::EMPLOYER_CONTRIBUTIONS_EXPENSE => 'مصروف مساهمات صاحب العمل',
            self::SALARIES_PAYABLE => 'رواتب مستحقة الدفع',
            self::SOCIAL_INSURANCE_PAYABLE => 'تأمينات اجتماعية مستحقة',
            self::INCOME_TAX_PAYABLE => 'ضريبة دخل مستحقة',
        };
    }

    function color(): string
    {
        return match($this) {
            AccountTypeEnum::CUSTOMER => 'primary',
            AccountTypeEnum::SUPPLIER => 'warning',
            AccountTypeEnum::CHECKS_UNDER_COLLECTION => 'danger',
            AccountTypeEnum::ISSUED_CHECKS => 'danger',
            AccountTypeEnum::CASH_OVER_SHORT => 'danger',
            AccountTypeEnum::EXPENSE => 'danger',
            AccountTypeEnum::FINANCE_EXPENSE => 'danger',
            AccountTypeEnum::MARKETING_EXPENSE => 'danger',
            AccountTypeEnum::OPERATING_EXPENSE => 'danger',
            AccountTypeEnum::GENERAL_AND_ADMINISTRATIVE_EXPENSE => 'danger',
            AccountTypeEnum::MAINTENANCE_AND_DEPRECIATION_EXPENSE => 'danger',
            AccountTypeEnum::INVENTORY_EXPENSE => 'danger',
            AccountTypeEnum::FIXED_ASSET => 'info',
            AccountTypeEnum::ACCUMULATED_DEPRECIATION => 'info',
            AccountTypeEnum::DEPRECIATION_EXPENSE => 'danger',
            AccountTypeEnum::GAIN_LOSS_ON_DISPOSAL => 'warning',
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
            AccountTypeEnum::PURCHASE_PRICE_VARIANCE => 'warning',
            self::UNEARNED_REVENUE => 'secondary',
            self::ACCRUED_REVENUE => 'secondary',
            self::PREPAID_ASSET => 'info',
            self::ACCRUED_EXPENSES => 'dark',
            self::SALARIES_EXPENSE => 'danger',
            self::EMPLOYER_CONTRIBUTIONS_EXPENSE => 'danger',
            self::SALARIES_PAYABLE => 'dark',
            self::SOCIAL_INSURANCE_PAYABLE => 'dark',
            self::INCOME_TAX_PAYABLE => 'dark',
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

    /**
     * Payment-capable = the tender an operator actually receives/pays with (cash, bank,
     * owner drawings). Subsidiary accounts (customer/supplier), the check-clearing
     * accounts (checks_under_collection/issued_checks are posted automatically, never
     * picked by hand), and every nominal/control account (sales, cogs, inventory, vat,
     * expenses, fixed assets, ...) are excluded.
     */
    function isPaymentCapable(): bool {
        return in_array($this, [self::BRANCH_CASH, self::OWNER_ACCOUNT]);
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
