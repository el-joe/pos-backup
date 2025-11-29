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
        /**
         *           id
         *           tenant_id
         *           plan_id
         *           plan_details : json
         *           price
         *           systems_allowed : POS | Bookings | HRM
         *           start_date
         *           end_date
         *           status : paid | cancelled | refunded
         *           payment_details : json
         *           payment_callback_details : json
         *           created_at
         *           updated_at
         */
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('plan_id');
            $table->json('plan_details');
            $table->decimal('price', 8, 2);
            $table->json('systems_allowed');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->enum('status', ['paid', 'cancelled', 'refunded'])->default('paid');
            $table->string('payment_gateway')->nullable();
            $table->json('payment_details');
            $table->json('payment_callback_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
