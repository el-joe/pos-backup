<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->decimal('paid_amount', 14, 2)->default(0)->after('cost');
        });

        // Backfill: historically fixed-assets were recorded as fully paid at creation.
        DB::table('fixed_assets')->where('cost', '>', 0)->update([
            'paid_amount' => DB::raw('cost'),
        ]);
    }

    public function down(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
};
