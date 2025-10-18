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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('admin_id')->index();
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->decimal('total_sales', 10, 2)->default(0);
            $table->decimal('total_sale_refunds', 10, 2)->default(0);
            $table->decimal('total_purchases', 10, 2)->default(0);
            $table->decimal('total_purchase_refunds', 10, 2)->default(0);
            $table->decimal('total_expenses', 10, 2)->default(0);
            $table->decimal('total_expense_refunds', 10, 2)->default(0);
            $table->decimal('total_deposits', 10, 2)->default(0);
            $table->decimal('total_withdrawals', 10, 2)->default(0);
            $table->decimal('closing_balance', 10, 2)->default(0);
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
