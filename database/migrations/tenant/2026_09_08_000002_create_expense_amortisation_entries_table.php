<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_amortisation_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->unique(['expense_id', 'period_year', 'period_month'], 'expense_amortisation_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_amortisation_entries');
    }
};
