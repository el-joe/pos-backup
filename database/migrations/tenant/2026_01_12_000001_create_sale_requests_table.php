<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->string('quote_number')->unique();
            $table->timestamp('request_date')->nullable();
            $table->timestamp('valid_until')->nullable();

            $table->string('status')->default('draft');

            $table->unsignedBigInteger('tax_id')->nullable()->index();
            $table->decimal('tax_percentage', 12, 2)->default(0);
            $table->unsignedBigInteger('discount_id')->nullable()->index();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('max_discount_amount', 12, 2)->default(0);

            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_requests');
    }
};
