<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'three_months_free')) {
                $table->boolean('three_months_free')->default(false)->after('price_year');
            }

            if (Schema::hasColumn('plans', 'discount_percent')) {
                $table->dropColumn('discount_percent');
            }

            if (Schema::hasColumn('plans', 'multi_system_discount_percent')) {
                $table->dropColumn('multi_system_discount_percent');
            }

            if (Schema::hasColumn('plans', 'free_trial_months')) {
                $table->dropColumn('free_trial_months');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'discount_percent')) {
                $table->decimal('discount_percent', 5, 2)->default(0)->after('price_year');
            }

            if (!Schema::hasColumn('plans', 'multi_system_discount_percent')) {
                $table->decimal('multi_system_discount_percent', 5, 2)->default(0)->after('discount_percent');
            }

            if (!Schema::hasColumn('plans', 'free_trial_months')) {
                $table->unsignedSmallInteger('free_trial_months')->default(0)->after('multi_system_discount_percent');
            }

            if (Schema::hasColumn('plans', 'three_months_free')) {
                $table->dropColumn('three_months_free');
            }
        });
    }
};
