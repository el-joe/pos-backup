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
            if (!Schema::hasColumn('plans', 'type')) {
                $table->string('type')->default('monthly')->after('name_ar');
            }
            if (!Schema::hasColumn('plans', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('type');
            }
        });

        DB::table('plans')->where('slug', 'monthly')->update([
            'type' => 'monthly',
            'price' => DB::raw('price_month'),
        ]);

        DB::table('plans')->where('slug', 'annual')->update([
            'type' => 'yearly',
            'price' => DB::raw('price_year'),
        ]);

        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'price_month')) {
                $table->dropColumn('price_month');
            }
            if (Schema::hasColumn('plans', 'price_year')) {
                $table->dropColumn('price_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'price_month')) {
                $table->decimal('price_month', 10, 2)->default(0)->after('name_ar');
            }
            if (!Schema::hasColumn('plans', 'price_year')) {
                $table->decimal('price_year', 10, 2)->default(0)->after('price_month');
            }
        });

        DB::table('plans')->where('type', 'monthly')->update(['price_month' => DB::raw('price')]);
        DB::table('plans')->where('type', 'yearly')->update(['price_year' => DB::raw('price')]);

        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('plans', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
