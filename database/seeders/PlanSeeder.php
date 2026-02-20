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
        $features = [
            ['code' => 'branches', 'module_name' => 'pos', 'name_ar' => 'الفروع', 'name_en' => 'Branches', 'type' => 'text', 'active' => true],
            ['code' => 'admins', 'module_name' => 'pos', 'name_ar' => 'المستخدمون الإداريون', 'name_en' => 'Admins', 'type' => 'text', 'active' => true],
            ['code' => 'products', 'module_name' => 'pos', 'name_ar' => 'المنتجات', 'name_en' => 'Products', 'type' => 'text', 'active' => true],
            ['code' => 'inventory', 'module_name' => 'pos', 'name_ar' => 'إدارة المخزون', 'name_en' => 'Inventory Management', 'type' => 'boolean', 'active' => true],
            ['code' => 'sales', 'module_name' => 'pos', 'name_ar' => 'إدارة المبيعات', 'name_en' => 'Sales Management', 'type' => 'boolean', 'active' => true],
            ['code' => 'purchases', 'module_name' => 'pos', 'name_ar' => 'إدارة المشتريات', 'name_en' => 'Purchases Management', 'type' => 'boolean', 'active' => true],
            ['code' => 'advanced_reports', 'module_name' => 'pos', 'name_ar' => 'تقارير متقدمة', 'name_en' => 'Advanced Reports', 'type' => 'boolean', 'active' => true],
            ['code' => 'pos_support', 'module_name' => 'pos', 'name_ar' => 'مستوى الدعم', 'name_en' => 'Support Level', 'type' => 'text', 'active' => true],

            ['code' => 'hrm_employees', 'module_name' => 'hrm', 'name_ar' => 'عدد الموظفين', 'name_en' => 'Employees', 'type' => 'text', 'active' => true],
            ['code' => 'hrm_payroll', 'module_name' => 'hrm', 'name_ar' => 'نظام الرواتب', 'name_en' => 'Payroll', 'type' => 'boolean', 'active' => true],
            ['code' => 'hrm_attendance', 'module_name' => 'hrm', 'name_ar' => 'الحضور والانصراف', 'name_en' => 'Attendance', 'type' => 'boolean', 'active' => true],
            ['code' => 'hrm_support', 'module_name' => 'hrm', 'name_ar' => 'مستوى الدعم', 'name_en' => 'Support Level', 'type' => 'text', 'active' => true],

            ['code' => 'booking_calendars', 'module_name' => 'booking', 'name_ar' => 'عدد الجداول', 'name_en' => 'Calendars', 'type' => 'text', 'active' => true],
            ['code' => 'booking_online', 'module_name' => 'booking', 'name_ar' => 'الحجز الإلكتروني', 'name_en' => 'Online Booking', 'type' => 'boolean', 'active' => true],
            ['code' => 'booking_reminders', 'module_name' => 'booking', 'name_ar' => 'التذكير التلقائي', 'name_en' => 'Automated Reminders', 'type' => 'boolean', 'active' => true],
            ['code' => 'booking_support', 'module_name' => 'booking', 'name_ar' => 'مستوى الدعم', 'name_en' => 'Support Level', 'type' => 'text', 'active' => true],
        ];

        Feature::query()->upsert(
            $features,
            ['code'],
            ['module_name', 'name_ar', 'name_en', 'type', 'active', 'updated_at']
        );

        $plans = [
            ['name' => 'POS Basic', 'module_name' => 'pos', 'price_month' => 0, 'price_year' => 0, 'three_months_free' => true, 'slug' => 'pos-basic', 'active' => true, 'recommended' => false, 'icon' => 'bi bi-star'],
            ['name' => 'POS Pro', 'module_name' => 'pos', 'price_month' => 19, 'price_year' => 190, 'three_months_free' => false, 'slug' => 'pos-pro', 'active' => true, 'recommended' => true, 'icon' => 'bi bi-lightning-charge-fill'],
            ['name' => 'POS Enterprise', 'module_name' => 'pos', 'price_month' => 39, 'price_year' => 390, 'three_months_free' => false, 'slug' => 'pos-enterprise', 'active' => true, 'recommended' => false, 'icon' => 'bi bi-gem'],

            ['name' => 'HRM Basic', 'module_name' => 'hrm', 'price_month' => 9, 'price_year' => 90, 'three_months_free' => false, 'slug' => 'hrm-basic', 'active' => true, 'recommended' => false, 'icon' => 'bi bi-people'],
            ['name' => 'HRM Pro', 'module_name' => 'hrm', 'price_month' => 29, 'price_year' => 290, 'three_months_free' => false, 'slug' => 'hrm-pro', 'active' => true, 'recommended' => true, 'icon' => 'bi bi-people-fill'],
            ['name' => 'HRM Enterprise', 'module_name' => 'hrm', 'price_month' => 59, 'price_year' => 590, 'three_months_free' => false, 'slug' => 'hrm-enterprise', 'active' => true, 'recommended' => false, 'icon' => 'bi bi-person-badge'],

            ['name' => 'Booking Basic', 'module_name' => 'booking', 'price_month' => 7, 'price_year' => 70, 'three_months_free' => false, 'slug' => 'booking-basic', 'active' => true, 'recommended' => false, 'icon' => 'bi bi-calendar2-week'],
            ['name' => 'Booking Pro', 'module_name' => 'booking', 'price_month' => 17, 'price_year' => 170, 'three_months_free' => false, 'slug' => 'booking-pro', 'active' => true, 'recommended' => true, 'icon' => 'bi bi-calendar-check'],
            ['name' => 'Booking Enterprise', 'module_name' => 'booking', 'price_month' => 34, 'price_year' => 340, 'three_months_free' => false, 'slug' => 'booking-enterprise', 'active' => true, 'recommended' => false, 'icon' => 'bi bi-calendar-event'],
        ];

        Plan::query()->upsert(
            $plans,
            ['slug'],
            ['name', 'module_name', 'price_month', 'price_year', 'three_months_free', 'active', 'recommended', 'icon', 'updated_at']
        );

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

        $add('pos-basic', 'branches', 1, '1 فرع', '1 branch');
        $add('pos-basic', 'admins', 1, '1 مدير', '1 admin');
        $add('pos-basic', 'products', 100, 'حتى 100 منتج', 'Up to 100 products');
        $add('pos-basic', 'inventory', 0);
        $add('pos-basic', 'sales', 1);
        $add('pos-basic', 'purchases', 1);
        $add('pos-basic', 'advanced_reports', 0);
        $add('pos-basic', 'pos_support', 1, 'دعم عبر البريد', 'Email support');

        $add('pos-pro', 'branches', 5, '5 فروع', '5 branches');
        $add('pos-pro', 'admins', 10, '10 مديرين', '10 admins');
        $add('pos-pro', 'products', 1000, 'حتى 1000 منتج', 'Up to 1000 products');
        $add('pos-pro', 'inventory', 1);
        $add('pos-pro', 'sales', 1);
        $add('pos-pro', 'purchases', 1);
        $add('pos-pro', 'advanced_reports', 1);
        $add('pos-pro', 'pos_support', 1, 'دعم قياسي', 'Standard support');

        $add('pos-enterprise', 'branches', 999999, 'فروع غير محدودة', 'Unlimited branches');
        $add('pos-enterprise', 'admins', 999999, 'مديرون غير محدودين', 'Unlimited admins');
        $add('pos-enterprise', 'products', 999999, 'منتجات غير محدودة', 'Unlimited products');
        $add('pos-enterprise', 'inventory', 1);
        $add('pos-enterprise', 'sales', 1);
        $add('pos-enterprise', 'purchases', 1);
        $add('pos-enterprise', 'advanced_reports', 1);
        $add('pos-enterprise', 'pos_support', 1, 'دعم VIP', 'VIP support');

        $add('hrm-basic', 'hrm_employees', 25, 'حتى 25 موظف', 'Up to 25 employees');
        $add('hrm-basic', 'hrm_payroll', 1);
        $add('hrm-basic', 'hrm_attendance', 0);
        $add('hrm-basic', 'hrm_support', 1, 'دعم عبر البريد', 'Email support');

        $add('hrm-pro', 'hrm_employees', 150, 'حتى 150 موظف', 'Up to 150 employees');
        $add('hrm-pro', 'hrm_payroll', 1);
        $add('hrm-pro', 'hrm_attendance', 1);
        $add('hrm-pro', 'hrm_support', 1, 'دعم قياسي', 'Standard support');

        $add('hrm-enterprise', 'hrm_employees', 999999, 'موظفون غير محدودين', 'Unlimited employees');
        $add('hrm-enterprise', 'hrm_payroll', 1);
        $add('hrm-enterprise', 'hrm_attendance', 1);
        $add('hrm-enterprise', 'hrm_support', 1, 'دعم VIP', 'VIP support');

        $add('booking-basic', 'booking_calendars', 1, 'جدول واحد', '1 calendar');
        $add('booking-basic', 'booking_online', 1);
        $add('booking-basic', 'booking_reminders', 0);
        $add('booking-basic', 'booking_support', 1, 'دعم عبر البريد', 'Email support');

        $add('booking-pro', 'booking_calendars', 5, '5 جداول', '5 calendars');
        $add('booking-pro', 'booking_online', 1);
        $add('booking-pro', 'booking_reminders', 1);
        $add('booking-pro', 'booking_support', 1, 'دعم قياسي', 'Standard support');

        $add('booking-enterprise', 'booking_calendars', 999999, 'جداول غير محدودة', 'Unlimited calendars');
        $add('booking-enterprise', 'booking_online', 1);
        $add('booking-enterprise', 'booking_reminders', 1);
        $add('booking-enterprise', 'booking_support', 1, 'دعم VIP', 'VIP support');

        PlanFeature::query()->whereIn('plan_id', $plansBySlug->pluck('id')->all())->delete();
        PlanFeature::query()->insert($rows);
    }
}
