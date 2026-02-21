<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_request_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('unit_id')->index();
            $table->integer('qty')->default(0);
            $table->decimal('purchase_price')->default(0);
            $table->decimal('discount_percentage', 10, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->integer('x_margin')->default(0);
            $table->decimal('sell_price')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_request_items');
    }
};
