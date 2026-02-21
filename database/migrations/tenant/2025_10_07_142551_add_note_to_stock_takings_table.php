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
        Schema::table('stock_takings', function (Blueprint $table) {
            $table->date('date')->nullable()->after('branch_id');
            $table->text('note')->nullable()->after('date');
        });

        Schema::table('stock_taking_products', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_id')->nullable()->after('product_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_takings', function (Blueprint $table) {
            //
        });
    }
};
