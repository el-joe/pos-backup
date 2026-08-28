<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->safeChange('purchase_items', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 4)->change();
        });

        $this->safeChange('purchase_items', function (Blueprint $table) {
            $table->decimal('sell_price', 15, 4)->change();
        });

        $this->safeChange('sale_items', function (Blueprint $table) {
            $table->decimal('unit_cost', 15, 4)->change();
        });

        $this->safeChange('sale_items', function (Blueprint $table) {
            $table->decimal('sell_price', 15, 4)->change();
        });

        $this->safeChange('purchase_request_items', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 4)->change();
        });

        $this->safeChange('purchase_request_items', function (Blueprint $table) {
            $table->decimal('sell_price', 15, 4)->change();
        });

        $this->safeChange('sale_request_items', function (Blueprint $table) {
            $table->decimal('unit_cost', 15, 4)->change();
        });

        $this->safeChange('sale_request_items', function (Blueprint $table) {
            $table->decimal('sell_price', 15, 4)->change();
        });

        $this->safeChange('stocks', function (Blueprint $table) {
            $table->decimal('qty', 10, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->safeChange('purchase_items', function (Blueprint $table) {
            $table->decimal('purchase_price', 8, 2)->change();
        });

        $this->safeChange('purchase_items', function (Blueprint $table) {
            $table->decimal('sell_price', 8, 2)->change();
        });

        $this->safeChange('sale_items', function (Blueprint $table) {
            $table->decimal('unit_cost', 10, 2)->change();
        });

        $this->safeChange('sale_items', function (Blueprint $table) {
            $table->decimal('sell_price', 10, 2)->change();
        });

        $this->safeChange('purchase_request_items', function (Blueprint $table) {
            $table->decimal('purchase_price', 8, 2)->change();
        });

        $this->safeChange('purchase_request_items', function (Blueprint $table) {
            $table->decimal('sell_price', 8, 2)->change();
        });

        $this->safeChange('sale_request_items', function (Blueprint $table) {
            $table->decimal('unit_cost', 10, 2)->change();
        });

        $this->safeChange('sale_request_items', function (Blueprint $table) {
            $table->decimal('sell_price', 10, 2)->change();
        });

        $this->safeChange('stocks', function (Blueprint $table) {
            $table->decimal('qty', 8, 2)->change();
        });
    }

    /**
     * Apply a single column change in isolation so a failure on one
     * column (e.g. missing table/column on a given tenant) does not
     * abort the rest of the migration.
     */
    private function safeChange(string $table, \Closure $callback): void
    {
        try {
            if (Schema::hasTable($table)) {
                Schema::table($table, $callback);
            } else {
                Log::warning("fix_financial_decimal_precision: table [{$table}] does not exist, skipping.");
            }
        } catch (\Throwable $e) {
            Log::error("fix_financial_decimal_precision: failed to alter table [{$table}]: {$e->getMessage()}");
        }
    }
};
