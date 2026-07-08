<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('features') || !Schema::hasTable('plans') || !Schema::hasTable('plan_features')) {
            return;
        }

        $now = now();

        DB::table('features')->updateOrInsert(
            ['code' => 'erp_admins'],
            [
                'module_name' => 'pos',
                'name_ar' => 'عدد المستخدمين الإداريين (ERP)',
                'name_en' => 'ERP Admin Users',
                'type' => 'text',
                'active' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $featureId = DB::table('features')->where('code', 'erp_admins')->value('id');
        if (!$featureId) {
            return;
        }

        $limits = [
            'starter' => [
                'value' => 2,
                'ar' => 'حتى 2 مستخدم إداري',
                'en' => 'Up to 2 admin users',
            ],
            'growth' => [
                'value' => 10,
                'ar' => 'حتى 10 مستخدمين إداريين',
                'en' => 'Up to 10 admin users',
            ],
            'enterprise' => [
                'value' => 999999,
                'ar' => 'مستخدمون إداريون غير محدودين',
                'en' => 'Unlimited admin users',
            ],
        ];

        $plans = DB::table('plans')
            ->whereIn('tier', array_keys($limits))
            ->get(['id', 'tier']);

        foreach ($plans as $plan) {
            $limit = $limits[$plan->tier] ?? null;
            if (!$limit) {
                continue;
            }

            DB::table('plan_features')->updateOrInsert(
                [
                    'plan_id' => $plan->id,
                    'feature_id' => $featureId,
                ],
                [
                    'value' => $limit['value'],
                    'content_ar' => $limit['ar'],
                    'content_en' => $limit['en'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('features') || !Schema::hasTable('plan_features')) {
            return;
        }

        $featureId = DB::table('features')->where('code', 'erp_admins')->value('id');
        if (!$featureId) {
            return;
        }

        DB::table('plan_features')->where('feature_id', $featureId)->delete();
        DB::table('features')->where('id', $featureId)->delete();
    }
};
