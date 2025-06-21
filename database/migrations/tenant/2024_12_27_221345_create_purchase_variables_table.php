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
        Schema::create('purchase_variables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->index();
            $table->unsignedBigInteger('product_variable_id')->index();
            $table->unsignedBigInteger('unit_id')->index();
            $table->integer('qty')->default(0);
            $table->decimal('purchase_price');
            $table->integer('discount_percentage')->default(0);
            $table->decimal('price')->comment('Purchase Price After Discount');
            $table->decimal('total')->comment('Purchase Price After Discount X qty');
            $table->integer('x_margin')->default(0);
            $table->decimal('sale_price')->comment('sale price for each qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_variables');
    }
};
