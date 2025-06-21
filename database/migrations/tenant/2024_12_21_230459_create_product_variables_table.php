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
        Schema::create('product_variables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('name');
            $table->string('sku')->unique()->index();
            $table->decimal('purchase_price_ex_tax', 10, 2)->default(0);
            $table->decimal('purchase_price_inc_tax', 10, 2)->default(0);
            $table->decimal('x_margin', 10, 2)->default(0)->comment('In percentage');
            $table->decimal('sell_price_ex_tax', 10, 2)->default(0);
            $table->decimal('sell_price_inc_tax', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variables');
    }
};
