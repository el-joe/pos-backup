<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * IDs soft-deleted by this migration because customer_id/supplier_id
     * could not be resolved from the payable. Hardcoded here so down()
     * can restore them without relying on the log output.
     */
    private array $softDeletedIds = [];

    public function up(): void
    {
        DB::transaction(function () {
            // 1. received checks missing customer_id -> resolve from sales
            DB::statement("
                UPDATE checks
                INNER JOIN sales ON sales.id = checks.payable_id
                SET checks.customer_id = sales.customer_id
                WHERE checks.direction = 'received'
                  AND checks.customer_id IS NULL
                  AND checks.payable_type LIKE '%Sale%'
                  AND checks.deleted_at IS NULL
            ");

            // 2a. issued checks missing supplier_id -> resolve from purchases
            DB::statement("
                UPDATE checks
                INNER JOIN purchases ON purchases.id = checks.payable_id
                SET checks.supplier_id = purchases.supplier_id
                WHERE checks.direction = 'issued'
                  AND checks.supplier_id IS NULL
                  AND checks.payable_type LIKE '%Purchase%'
                  AND checks.deleted_at IS NULL
            ");

            // 2b. issued checks missing supplier_id -> resolve from the purchase(s)
            // tied to the fixed asset. The current schema has no FK linking
            // fixed_assets to purchases/suppliers, so this can only resolve
            // checks whose payable_id already matches a purchases.id record
            // (e.g. the asset was purchased through the purchases table under
            // a shared id space). Anything it can't resolve falls through to
            // step 3 below and is soft-deleted.
            DB::statement("
                UPDATE checks
                INNER JOIN fixed_assets ON fixed_assets.id = checks.payable_id
                INNER JOIN purchases ON purchases.id = fixed_assets.id
                SET checks.supplier_id = purchases.supplier_id
                WHERE checks.direction = 'issued'
                  AND checks.supplier_id IS NULL
                  AND checks.payable_type LIKE '%FixedAsset%'
                  AND checks.deleted_at IS NULL
            ");

            // 3. Anything still unresolved gets soft-deleted and logged.
            $unresolved = DB::table('checks')
                ->whereNull('deleted_at')
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('direction', 'received')->whereNull('customer_id');
                    })->orWhere(function ($q) {
                        $q->where('direction', 'issued')->whereNull('supplier_id');
                    });
                })
                ->pluck('id')
                ->all();

            if (!empty($unresolved)) {
                $this->softDeletedIds = $unresolved;

                DB::table('checks')
                    ->whereIn('id', $unresolved)
                    ->update(['deleted_at' => now()]);

                Log::warning('Soft-deleted unresolvable checks: ' . implode(',', $unresolved));
            }

            // 4. Backfill missing check_number with a traceable auto-number.
            DB::statement("
                UPDATE checks
                SET check_number = CONCAT('AUTO-', id, '-', DATE_FORMAT(created_at, '%Y%m%d'))
                WHERE check_number IS NULL
            ");
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            DB::table('checks')
                ->where('check_number', 'LIKE', 'AUTO-%')
                ->update(['check_number' => null]);

            if (!empty($this->softDeletedIds)) {
                DB::table('checks')
                    ->whereIn('id', $this->softDeletedIds)
                    ->update(['deleted_at' => null]);
            }
        });
    }
};
