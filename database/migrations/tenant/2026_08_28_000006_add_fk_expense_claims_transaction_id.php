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
            DB::statement("UPDATE expense_claims ec
                LEFT JOIN transactions t ON ec.transaction_id = t.id
                SET ec.transaction_id = NULL
                WHERE ec.transaction_id IS NOT NULL AND t.id IS NULL");
        });

        Schema::table('expense_claims', function (Blueprint $table) {
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('expense_claims', fn (Blueprint $table) => $table->dropForeign(['transaction_id']));
    }
};
