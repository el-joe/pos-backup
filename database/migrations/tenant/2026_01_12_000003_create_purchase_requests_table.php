<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->string('request_number')->unique();
            $table->timestamp('request_date')->nullable();
            $table->string('status')->default('draft');

            $table->unsignedBigInteger('tax_id')->nullable()->index();
            $table->decimal('tax_percentage', 12, 2)->default(0);
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);

            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
