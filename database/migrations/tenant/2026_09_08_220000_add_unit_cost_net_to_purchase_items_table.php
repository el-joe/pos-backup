<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            // Cost capitalised into inventory: line price net of discount, excluding recoverable
            // input tax and excluding any pro-rata share of the order-level discount/expenses.
            // Populated at save time; historical rows are left null (see prompt 09 for repair).
            $table->decimal('unit_cost_net', 15, 4)->nullable()->after('purchase_price');
        });

        Schema::table('purchases', function (Blueprint $table) {
            // 'trade': discount reduces cost of purchase (IAS 2 §11), the default.
            // 'settlement': early-payment discount, recognised as finance income instead.
            $table->enum('discount_classification', ['trade', 'settlement'])->default('trade')->after('discount_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('unit_cost_net');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('discount_classification');
        });
    }
};
