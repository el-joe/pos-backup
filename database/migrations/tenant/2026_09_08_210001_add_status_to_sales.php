<?php

use App\Models\Tenant\Sale;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('is_deferred');
        });

        // Backfill only — derives status from the existing paid_amount / computed grand total.
        // Uses the base query builder (not the Eloquent model) so it never touches
        // updated_at, paid_amount, or any ledger row.
        Sale::query()->with('saleItems')->chunkById(200, function ($sales) {
            foreach ($sales as $sale) {
                $due = $sale->due_amount;
                $total = $sale->grand_total_amount;

                $status = match (true) {
                    $sale->refund_status?->value === 'full_refund' => 'refunded',
                    $due <= 0 && $total > 0 => 'full_paid',
                    $sale->paid_amount > 0 && $due > 0 => 'partial_paid',
                    default => 'pending',
                };

                DB::table('sales')->whereKey($sale->id)->update(['status' => $status]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
