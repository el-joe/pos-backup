<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'tier')) {
                $table->string('tier')->nullable()->after('name');
            }

            if (!Schema::hasColumn('plans', 'sort_order')) {
                $table->unsignedTinyInteger('sort_order')->default(99)->after('recommended');
            }
        });

        if (Schema::hasTable('plan_features')) {
            DB::statement('DELETE pf1 FROM plan_features pf1 INNER JOIN plan_features pf2 WHERE pf1.id > pf2.id AND pf1.plan_id = pf2.plan_id AND pf1.feature_id = pf2.feature_id');

            Schema::table('plan_features', function (Blueprint $table) {
                $table->unique(['plan_id', 'feature_id'], 'plan_features_plan_feature_unique');
            });
        }

        $mainPlans = DB::table('plans')
            ->where('active', true)
            ->orderByDesc('recommended')
            ->orderBy('price_month')
            ->orderBy('id')
            ->limit(3)
            ->get(['id']);

        if ($mainPlans->count() === 0) {
            $now = now();
            $mainPlans = collect([
                [
                    'name' => 'Starter',
                    'tier' => 'starter',
                    'price_month' => 29,
                    'price_year' => 24,
                    'three_months_free' => false,
                    'slug' => 'starter',
                    'active' => true,
                    'recommended' => false,
                    'sort_order' => 1,
                    'module_name' => 'pos',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Growth',
                    'tier' => 'growth',
                    'price_month' => 69,
                    'price_year' => 55,
                    'three_months_free' => true,
                    'slug' => 'growth',
                    'active' => true,
                    'recommended' => true,
                    'sort_order' => 2,
                    'module_name' => 'pos',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Enterprise',
                    'tier' => 'enterprise',
                    'price_month' => 129,
                    'price_year' => 99,
                    'three_months_free' => true,
                    'slug' => 'enterprise',
                    'active' => true,
                    'recommended' => false,
                    'sort_order' => 3,
                    'module_name' => 'pos',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ])->map(function (array $row) {
                $id = DB::table('plans')->insertGetId($row);
                return (object) ['id' => $id];
            });
        }

        $mainPlanIds = $mainPlans->pluck('id')->values()->all();

        DB::table('plans')
            ->whereIn('id', $mainPlanIds)
            ->update(['active' => true]);

        DB::table('plans')
            ->whereNotIn('id', $mainPlanIds)
            ->update([
                'active' => false,
                'recommended' => false,
                'sort_order' => 99,
            ]);

        $tiers = ['starter', 'growth', 'enterprise'];
        foreach ($mainPlanIds as $index => $planId) {
            DB::table('plans')
                ->where('id', $planId)
                ->update([
                    'tier' => $tiers[$index] ?? null,
                    'sort_order' => $index + 1,
                    'updated_at' => now(),
                ]);
        }

        if (Schema::hasTable('features') && Schema::hasTable('plan_features')) {
            $activeFeatureIds = DB::table('features')
                ->where('active', true)
                ->orderBy('id')
                ->pluck('id')
                ->all();

            foreach ($mainPlanIds as $planId) {
                foreach ($activeFeatureIds as $featureId) {
                    $exists = DB::table('plan_features')
                        ->where('plan_id', $planId)
                        ->where('feature_id', $featureId)
                        ->exists();

                    if (!$exists) {
                        DB::table('plan_features')->insert([
                            'plan_id' => $planId,
                            'feature_id' => $featureId,
                            'value' => 0,
                            'content_ar' => null,
                            'content_en' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('plan_features')) {
            Schema::table('plan_features', function (Blueprint $table) {
                $table->dropUnique('plan_features_plan_feature_unique');
            });
        }

        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'tier')) {
                $table->dropColumn('tier');
            }

            if (Schema::hasColumn('plans', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
