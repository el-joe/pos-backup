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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('sku')->unique()->index();
            $table->unsignedBigInteger('unit_id')->index()->nullable();
            $table->unsignedBigInteger('brand_id')->index()->nullable();
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->decimal('weight', 10, 2)->default(0);
            $table->integer('alert_qty')->default(0);
            $table->boolean('active')->default(true);
            // $table->enum('type',['single','multiple'])->default('single');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->decimal('tax_rate', 10, 2)->default(0)->comment('In percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
