<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // discounts.type: enum('rate','fixed') -> enum('percentage','fixed')
        DB::statement("ALTER TABLE discounts MODIFY COLUMN type VARCHAR(20) NOT NULL");
        DB::table('discounts')->where('type', 'rate')->update(['type' => 'percentage']);
        DB::statement("ALTER TABLE discounts MODIFY COLUMN type ENUM('percentage','fixed') NOT NULL");

        // sales.discount_type: free varchar, currently holding 'rate'/'fixed' (or null)
        DB::table('sales')->where('discount_type', 'rate')->update(['discount_type' => 'percentage']);
        Schema::table('sales', function (Blueprint $table) {
            $table->string('discount_type', 20)->nullable()->default(null)->change();
        });

        // purchases.discount_type already enum('fixed','percentage') - vocabulary already matches, no data change needed.
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE discounts MODIFY COLUMN type VARCHAR(20) NOT NULL");
        DB::table('discounts')->where('type', 'percentage')->update(['type' => 'rate']);
        DB::statement("ALTER TABLE discounts MODIFY COLUMN type ENUM('rate','fixed') NOT NULL");

        DB::table('sales')->where('discount_type', 'percentage')->update(['discount_type' => 'rate']);
    }
};
