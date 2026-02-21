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
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->string('status')->default('pending'); // pending , partial_paid , full_paid , refunded , canceled
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->integer('refunded_qty')->default(0);
            $table->timestamp('refunded_at')->nullable();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('note')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
