<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Contracting\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Current Assets / الأصول المتداولة', 'type' => 'asset', 'parent_code' => null],
            ['code' => '1010', 'name' => 'Cash at Hand / نقدية بالخزينة', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1020', 'name' => 'Accounts Receivable / ذمم مدينة', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1030', 'name' => 'Checks Under Collection / شيكات تحت التحصيل', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1040', 'name' => 'Inventory / المخزون', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1050', 'name' => 'VAT Receivable / ضريبة مستردة', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1100', 'name' => 'Fixed Assets / الأصول الثابتة', 'type' => 'asset', 'parent_code' => null],
            ['code' => '1110', 'name' => 'Fixed Asset Cost / تكلفة الأصول الثابتة', 'type' => 'asset', 'parent_code' => '1100'],

            // Liabilities
            ['code' => '2000', 'name' => 'Current Liabilities / المطلوبات المتداولة', 'type' => 'liability', 'parent_code' => null],
            ['code' => '2010', 'name' => 'Accounts Payable / ذمم دائنة', 'type' => 'liability', 'parent_code' => '2000'],
            ['code' => '2020', 'name' => 'VAT Payable / ضريبة مستحقة', 'type' => 'liability', 'parent_code' => '2000'],
            ['code' => '2030', 'name' => 'Issued Checks / شيكات مصدرة', 'type' => 'liability', 'parent_code' => '2000'],
            ['code' => '2100', 'name' => 'Long-term Liabilities / مطلوبات طويلة الأجل', 'type' => 'liability', 'parent_code' => null],

            // Equity
            ['code' => '3000', 'name' => 'Owner Equity / حقوق الملكية', 'type' => 'equity', 'parent_code' => null],
            ['code' => '3010', 'name' => 'Owner Account / حساب المالك', 'type' => 'equity', 'parent_code' => '3000'],

            // Revenue
            ['code' => '4000', 'name' => 'Revenue / الإيرادات', 'type' => 'revenue', 'parent_code' => null],
            ['code' => '4010', 'name' => 'Sales Revenue / إيرادات المبيعات', 'type' => 'revenue', 'parent_code' => '4000'],
            ['code' => '4020', 'name' => 'Sales Discount / خصم المبيعات', 'type' => 'revenue', 'parent_code' => '4000'],

            // Expenses
            ['code' => '5000', 'name' => 'Cost of Goods Sold / تكلفة البضاعة المباعة', 'type' => 'expense', 'parent_code' => null],
            ['code' => '5010', 'name' => 'COGS / تكلفة البضاعة', 'type' => 'expense', 'parent_code' => '5000'],
            ['code' => '6000', 'name' => 'Operating Expenses / مصروفات تشغيلية', 'type' => 'expense', 'parent_code' => null],
            ['code' => '6010', 'name' => 'Purchase Discount / خصم المشتريات', 'type' => 'expense', 'parent_code' => '6000'],
            ['code' => '6020', 'name' => 'General Expenses / مصروفات عامة', 'type' => 'expense', 'parent_code' => '6000'],
            ['code' => '6030', 'name' => 'Inventory Shortage / عجز المخزون', 'type' => 'expense', 'parent_code' => '6000'],
        ];

        $idsByCode = [];

        // Pass 1: create/update top-level and child accounts without parent_id first,
        // so children can reference the actual DB id of their parent regardless of order.
        foreach ($accounts as $account) {
            $chartOfAccount = ChartOfAccount::firstOrCreate(
                ['code' => $account['code']],
                [
                    'name' => $account['name'],
                    'type' => $account['type'],
                    'parent_id' => null,
                    'is_active' => true,
                ]
            );

            $idsByCode[$account['code']] = $chartOfAccount->id;
        }

        // Pass 2: wire up parent_id now that every code has a resolved id.
        foreach ($accounts as $account) {
            if ($account['parent_code'] === null) {
                continue;
            }

            ChartOfAccount::where('code', $account['code'])->update([
                'name' => $account['name'],
                'type' => $account['type'],
                'parent_id' => $idsByCode[$account['parent_code']],
            ]);
        }
    }
}
