<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            DB::statement('
                CREATE TEMPORARY TABLE tmp_stock_keepers AS
                SELECT MIN(id) as keep_id, product_id, unit_id, branch_id, SUM(qty) as total_qty
                FROM stocks
                GROUP BY product_id, unit_id, branch_id
                HAVING COUNT(*) > 1
            ');

            $duplicateGroups = DB::table('tmp_stock_keepers')->count();

            DB::statement('
                UPDATE stocks s
                JOIN tmp_stock_keepers t ON s.id = t.keep_id
                SET s.qty = t.total_qty
            ');

            DB::statement('
                UPDATE stocks s
                JOIN tmp_stock_keepers t
                    ON s.product_id = t.product_id
                    AND s.unit_id = t.unit_id
                    AND s.branch_id = t.branch_id
                JOIN (
                    SELECT product_id, unit_id, branch_id, MAX(updated_at) as newest_updated_at
                    FROM stocks
                    GROUP BY product_id, unit_id, branch_id
                    HAVING COUNT(*) > 1
                ) newest
                    ON newest.product_id = t.product_id
                    AND newest.unit_id = t.unit_id
                    AND newest.branch_id = t.branch_id
                JOIN stocks src
                    ON src.product_id = t.product_id
                    AND src.unit_id = t.unit_id
                    AND src.branch_id = t.branch_id
                    AND src.updated_at = newest.newest_updated_at
                SET s.sell_price = src.sell_price,
                    s.unit_cost = src.unit_cost
                WHERE s.id = t.keep_id
            ');

            DB::statement('
                DELETE s FROM stocks s
                JOIN (SELECT product_id, unit_id, branch_id FROM tmp_stock_keepers) t
                    ON s.product_id = t.product_id
                    AND s.unit_id = t.unit_id
                    AND s.branch_id = t.branch_id
                WHERE s.id NOT IN (SELECT keep_id FROM tmp_stock_keepers)
            ');

            DB::statement('DROP TEMPORARY TABLE tmp_stock_keepers');

            Log::info("Collapsed {$duplicateGroups} duplicate stock groups before adding unique index");

            Schema::table('stocks', function ($table) {
                $table->unique(['product_id', 'unit_id', 'branch_id'], 'stocks_product_unit_branch_unique');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function ($table) {
            $table->dropUnique('stocks_product_unit_branch_unique');
        });
    }
};
