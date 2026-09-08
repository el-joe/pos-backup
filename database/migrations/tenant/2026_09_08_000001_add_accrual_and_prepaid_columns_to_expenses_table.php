<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_account_id')->nullable()->after('branch_id');
            $table->timestamp('accrued_at')->nullable()->after('type');
            $table->timestamp('settled_at')->nullable()->after('accrued_at');
            $table->date('amortisation_start_date')->nullable()->after('settled_at');
            $table->unsignedInteger('amortisation_months')->nullable()->after('amortisation_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn([
                'payment_account_id',
                'accrued_at',
                'settled_at',
                'amortisation_start_date',
                'amortisation_months',
            ]);
        });
    }
};
