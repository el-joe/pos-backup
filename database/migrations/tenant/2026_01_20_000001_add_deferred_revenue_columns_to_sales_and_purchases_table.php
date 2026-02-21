<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'is_deferred')) {
                $table->boolean('is_deferred')->default(false)->index();
            }
            if (!Schema::hasColumn('sales', 'inventory_delivered_at')) {
                $table->timestamp('inventory_delivered_at')->nullable()->index();
            }
        });

        Schema::table('purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('purchases', 'is_deferred')) {
                $table->boolean('is_deferred')->default(false)->index();
            }
            if (!Schema::hasColumn('purchases', 'inventory_received_at')) {
                $table->timestamp('inventory_received_at')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'inventory_delivered_at')) {
                $table->dropColumn('inventory_delivered_at');
            }
            if (Schema::hasColumn('sales', 'is_deferred')) {
                $table->dropColumn('is_deferred');
            }
        });

        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'inventory_received_at')) {
                $table->dropColumn('inventory_received_at');
            }
            if (Schema::hasColumn('purchases', 'is_deferred')) {
                $table->dropColumn('is_deferred');
            }
        });
    }
};
