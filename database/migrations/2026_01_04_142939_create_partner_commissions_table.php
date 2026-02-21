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
        Schema::create('partner_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');
            $table->string('tenant_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamp('commission_date');
            $table->timestamp('collected_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_commissions');
    }
};
