<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('counterparty_account_id')->nullable()->index()->after('account_id');
        });

        // account_id historically stored the counterparty (customer/supplier) account
        // rather than the payment account for Sales/Purchases. Preserve that value here
        // before account_id is corrected in a later prompt.
        DB::statement('UPDATE order_payments SET counterparty_account_id = account_id WHERE account_id IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropColumn('counterparty_account_id');
        });
    }
};
