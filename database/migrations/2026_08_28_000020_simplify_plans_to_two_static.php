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
            if (!Schema::hasColumn('plans', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
            }
            if (!Schema::hasColumn('plans', 'name_ar')) {
                $table->string('name_ar')->nullable()->after('name_en');
            }
        });

        $now = now();

        DB::table('plans')->updateOrInsert(
            ['slug' => 'monthly'],
            [
                'name' => 'Monthly Plan',
                'name_en' => 'Monthly Plan',
                'name_ar' => 'الخطة الشهرية',
                'price_month' => 99.00,
                'price_year' => 0,
                'active' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('plans')->updateOrInsert(
            ['slug' => 'annual'],
            [
                'name' => 'Annual Plan',
                'name_en' => 'Annual Plan',
                'name_ar' => 'الخطة السنوية',
                'price_month' => 0,
                'price_year' => 990.00,
                'active' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'name_en')) {
                $table->dropColumn('name_en');
            }
            if (Schema::hasColumn('plans', 'name_ar')) {
                $table->dropColumn('name_ar');
            }
        });
    }
};
