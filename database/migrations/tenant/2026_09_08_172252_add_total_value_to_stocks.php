<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Widen precision first (raw SQL — doctrine/dbal, needed by ->change(), isn't installed)
        // so the total_value backfill below doesn't truncate against the old decimal(10,2).
        DB::statement('ALTER TABLE `stocks` MODIFY `unit_cost` DECIMAL(15,4) NOT NULL DEFAULT 0');

        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('total_value', 18, 4)->default(0)->after('unit_cost');
        });

        // Backfill: total_value becomes the authoritative valuation, unit_cost is derived from it.
        DB::statement('UPDATE `stocks` SET `total_value` = `qty` * `unit_cost`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('total_value');
        });

        DB::statement('ALTER TABLE `stocks` MODIFY `unit_cost` DECIMAL(10,2) NOT NULL DEFAULT 0');
    }
};
