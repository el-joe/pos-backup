<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        PlanFeature::query()->delete();
        Feature::query()->delete();
        Plan::query()->delete();

        $plans = [
            ['name' => 'Starter', 'tier' => 'starter', 'module_name' => 'pos', 'price_month' => 49, 'price_year' => 39, 'three_months_free' => false, 'slug' => 'starter', 'active' => true, 'recommended' => false, 'sort_order' => 1],
            ['name' => 'Growth', 'tier' => 'growth', 'module_name' => 'pos', 'price_month' => 99, 'price_year' => 79, 'three_months_free' => true, 'slug' => 'growth', 'active' => true, 'recommended' => true, 'sort_order' => 2],
            ['name' => 'Enterprise', 'tier' => 'enterprise', 'module_name' => 'pos', 'price_month' => 179, 'price_year' => 149, 'three_months_free' => true, 'slug' => 'enterprise', 'active' => true, 'recommended' => false, 'sort_order' => 3],
        ];

        Plan::query()->insert(collect($plans)->map(fn (array $item) => $item + ['created_at' => now(), 'updated_at' => now()])->all());

        $features = [
            ['code' => 'erp_branches', 'module_name' => 'pos', 'name_ar' => 'عدد الفروع (ERP)', 'name_en' => 'ERP Branches', 'type' => 'text', 'active' => true],
            ['code' => 'erp_admins', 'module_name' => 'pos', 'name_ar' => 'عدد المستخدمين الإداريين (ERP)', 'name_en' => 'ERP Admin Users', 'type' => 'text', 'active' => true],
            ['code' => 'erp_products', 'module_name' => 'pos', 'name_ar' => 'عدد المنتجات (ERP)', 'name_en' => 'ERP Products', 'type' => 'text', 'active' => true],
            ['code' => 'erp_inventory', 'module_name' => 'pos', 'name_ar' => 'إدارة المخزون', 'name_en' => 'Inventory Management', 'type' => 'boolean', 'active' => true],
            ['code' => 'erp_sales', 'module_name' => 'pos', 'name_ar' => 'إدارة المبيعات', 'name_en' => 'Sales Management', 'type' => 'boolean', 'active' => true],
            ['code' => 'erp_accounting', 'module_name' => 'pos', 'name_ar' => 'المحاسبة المتقدمة', 'name_en' => 'Advanced Accounting', 'type' => 'boolean', 'active' => true],
            ['code' => 'hrm_employees', 'module_name' => 'hrm', 'name_ar' => 'عدد الموظفين (HRM)', 'name_en' => 'HRM Employees', 'type' => 'text', 'active' => true],
            ['code' => 'hrm_payroll', 'module_name' => 'hrm', 'name_ar' => 'إدارة الرواتب', 'name_en' => 'Payroll Management', 'type' => 'boolean', 'active' => true],
            ['code' => 'hrm_attendance', 'module_name' => 'hrm', 'name_ar' => 'إدارة الحضور', 'name_en' => 'Attendance Tracking', 'type' => 'boolean', 'active' => true],
            ['code' => 'hrm_leaves', 'module_name' => 'hrm', 'name_ar' => 'إدارة الإجازات', 'name_en' => 'Leave Management', 'type' => 'boolean', 'active' => true],
            ['code' => 'support_level', 'module_name' => 'pos', 'name_ar' => 'مستوى الدعم', 'name_en' => 'Support Level', 'type' => 'text', 'active' => true],
        ];

        Feature::query()->insert(collect($features)->map(fn (array $item) => $item + ['created_at' => now(), 'updated_at' => now()])->all());

        $plansBySlug = Plan::query()->whereIn('slug', collect($plans)->pluck('slug'))->get()->keyBy('slug');
        $featuresByCode = Feature::query()->whereIn('code', collect($features)->pluck('code'))->get()->keyBy('code');

        $rows = [];
        $add = function (string $planSlug, string $featureCode, int $value, ?string $contentAr = null, ?string $contentEn = null) use (&$rows, $plansBySlug, $featuresByCode): void {
            $plan = $plansBySlug->get($planSlug);
            $feature = $featuresByCode->get($featureCode);
            if (!$plan || !$feature) {
                return;
            }

            $rows[] = [
                'plan_id' => $plan->id,
                'feature_id' => $feature->id,
                'value' => $value,
                'content_ar' => $contentAr,
                'content_en' => $contentEn,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        };

        $add('starter', 'erp_branches', 1, 'فرع واحد', '1 branch');
        $add('starter', 'erp_admins', 2, 'حتى 2 مستخدم إداري', 'Up to 2 admin users');
        $add('starter', 'erp_products', 500, 'حتى 500 منتج', 'Up to 500 products');
        $add('starter', 'erp_inventory', 1);
        $add('starter', 'erp_sales', 1);
        $add('starter', 'erp_accounting', 0);
        $add('starter', 'hrm_employees', 25, 'حتى 25 موظف', 'Up to 25 employees');
        $add('starter', 'hrm_payroll', 1);
        $add('starter', 'hrm_attendance', 0);
        $add('starter', 'hrm_leaves', 0);
        $add('starter', 'support_level', 1, 'دعم عبر البريد', 'Email support');

        $add('growth', 'erp_branches', 5, 'حتى 5 فروع', 'Up to 5 branches');
        $add('growth', 'erp_admins', 10, 'حتى 10 مستخدمين إداريين', 'Up to 10 admin users');
        $add('growth', 'erp_products', 5000, 'حتى 5000 منتج', 'Up to 5000 products');
        $add('growth', 'erp_inventory', 1);
        $add('growth', 'erp_sales', 1);
        $add('growth', 'erp_accounting', 1);
        $add('growth', 'hrm_employees', 150, 'حتى 150 موظف', 'Up to 150 employees');
        $add('growth', 'hrm_payroll', 1);
        $add('growth', 'hrm_attendance', 1);
        $add('growth', 'hrm_leaves', 1);
        $add('growth', 'support_level', 1, 'دعم قياسي', 'Standard support');

        $add('enterprise', 'erp_branches', 999999, 'فروع غير محدودة', 'Unlimited branches');
        $add('enterprise', 'erp_admins', 999999, 'مستخدمون إداريون غير محدودين', 'Unlimited admin users');
        $add('enterprise', 'erp_products', 999999, 'منتجات غير محدودة', 'Unlimited products');
        $add('enterprise', 'erp_inventory', 1);
        $add('enterprise', 'erp_sales', 1);
        $add('enterprise', 'erp_accounting', 1);
        $add('enterprise', 'hrm_employees', 999999, 'موظفون غير محدودين', 'Unlimited employees');
        $add('enterprise', 'hrm_payroll', 1);
        $add('enterprise', 'hrm_attendance', 1);
        $add('enterprise', 'hrm_leaves', 1);
        $add('enterprise', 'support_level', 1, 'دعم VIP', 'VIP support');

        PlanFeature::query()->insert($rows);
    }
}
