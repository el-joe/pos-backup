<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('sales_threshold', 15, 2)->nullable()->after('max_discount_amount');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('sales_threshold', 15, 2)->nullable()->after('discount_value');
            $table->decimal('max_discount_amount', 15, 2)->nullable()->after('sales_threshold');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('sales_threshold');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['sales_threshold', 'max_discount_amount']);
        });
    }
};
