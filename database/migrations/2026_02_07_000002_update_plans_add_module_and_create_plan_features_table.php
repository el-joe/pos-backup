<?php

use App\Enums\PlanFeaturesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'module_name')) {
                $table->string('module_name')->default('pos')->after('name');
            }
        });

        if (!Schema::hasTable('plan_features')) {
            Schema::create('plan_features', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('plan_id');
                $table->unsignedBigInteger('feature_id');
                $table->integer('value')->default(0);
                $table->string('content')->nullable();
                $table->timestamps();
            });
        }

        // Migrate old plans.features JSON to plan_features table
        if (Schema::hasColumn('plans', 'features') && Schema::hasTable('features') && Schema::hasTable('plan_features')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('features');
            });
        }
    }

    public function down(): void
    {
        // best-effort: restore plans.features json and drop plan_features table
        if (!Schema::hasColumn('plans', 'features')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->json('features')->nullable()->after('price_year');
            });
        }

        if (Schema::hasTable('plan_features')) {
            Schema::drop('plan_features');
        }

        if (Schema::hasColumn('plans', 'module_name')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('module_name');
            });
        }
    }
};
