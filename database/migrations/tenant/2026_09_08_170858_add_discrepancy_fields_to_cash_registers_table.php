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
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->decimal('expected_closing_balance', 10, 2)->nullable()->after('closing_balance');
            $table->decimal('discrepancy', 10, 2)->nullable()->after('expected_closing_balance');
            $table->text('discrepancy_reason')->nullable()->after('discrepancy');
            $table->unsignedBigInteger('discrepancy_approved_by')->nullable()->index()->after('discrepancy_reason');
            $table->timestamp('discrepancy_approved_at')->nullable()->after('discrepancy_approved_by');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->dropColumn([
                'expected_closing_balance',
                'discrepancy',
                'discrepancy_reason',
                'discrepancy_approved_by',
                'discrepancy_approved_at',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
