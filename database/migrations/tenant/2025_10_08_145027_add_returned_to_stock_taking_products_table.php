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
        Schema::table('stock_taking_products', function (Blueprint $table) {
            $table->boolean('returned')->default(false)->after('actual_qty');
            $table->decimal('unit_cost', 15, 4)->default(0)->after('actual_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_taking_products', function (Blueprint $table) {
            //
        });
    }
};
