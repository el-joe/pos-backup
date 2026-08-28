<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            DB::statement("UPDATE payroll_runs pr
                LEFT JOIN transactions t ON pr.transaction_id = t.id
                SET pr.transaction_id = NULL
                WHERE pr.transaction_id IS NOT NULL AND t.id IS NULL");
        });

        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_runs', fn (Blueprint $table) => $table->dropForeign(['transaction_id']));
    }
};
