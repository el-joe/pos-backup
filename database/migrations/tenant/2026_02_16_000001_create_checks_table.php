<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('branch_id')->nullable()->index();

            // received (from customer) or issued (to supplier)
            $table->string('direction')->index();
            // received: under_collection|collected|bounced
            // issued: issued|cleared
            $table->string('status')->index();

            $table->morphs('payable');
            $table->unsignedBigInteger('order_payment_id')->nullable()->index();

            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();

            $table->decimal('amount', 15, 4);

            $table->string('check_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->date('check_date')->nullable();
            $table->date('due_date')->nullable();

            $table->string('note')->nullable();

            $table->unsignedBigInteger('collected_account_id')->nullable()->index();
            $table->unsignedBigInteger('cleared_account_id')->nullable()->index();

            $table->timestamp('collected_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('cleared_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checks');
    }
};
