<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // payroll_runs.status already exists (draft/approved/paid). Only branch scoping is missing.
        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');
            $table->unsignedBigInteger('approved_transaction_id')->nullable()->after('transaction_id');
        });

        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            $table->foreign('approved_transaction_id')->references('id')->on('transactions')->nullOnDelete();
        });

        // Employees have no branch scoping at all today; add it so multi-branch tenants
        // can attribute payroll cost/liability postings to the correct branch.
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('designation_id');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['approved_transaction_id']);
            $table->dropColumn(['branch_id', 'approved_transaction_id']);
        });
    }
};
