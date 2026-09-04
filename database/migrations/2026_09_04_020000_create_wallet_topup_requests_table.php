<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_topup_requests', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->decimal('amount', 12, 2);

            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->boolean('manual')->default(false);

            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->string('currency_code')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->decimal('conversion_rate', 12, 4)->nullable();
            $table->decimal('converted_amount', 14, 2)->nullable();

            $table->string('receipt_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_topup_requests');
    }
};
