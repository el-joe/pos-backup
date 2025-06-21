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
        Schema::create('sale_variables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id')->index();
            $table->unsignedBigInteger('product_variable_id')->index();
            $table->unsignedBigInteger('unit_id')->index();
            $table->integer('qty')->default(0);
            $table->decimal('sale_price');
            $table->enum('discount_type',['amount','percentage'])->nullable();
            $table->decimal('discount')->default(0);
            $table->integer('refunded')->default(0);
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_variables');
    }
};
