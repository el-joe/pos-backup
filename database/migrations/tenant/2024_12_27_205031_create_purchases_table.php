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
        // - Purchases : supplier , ref_no , order_date  , branch_id , doc_file , status (requested,pending,received)
        // - purchase_variables : purchase_id , product_variable_id , unit_id ,qty , purchase_price , discount_percentage , price(after discount) , total , x_margin_percentage , sell_price
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->index()->comment('contact_id which has supplier type');
            $table->unsignedBigInteger('branch_id')->index();
            $table->string('ref_no');
            $table->timestamp('order_date')->nullable();
            $table->enum('status',['requested','pending','received'])->default('requested');
            // Doc File from Files
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
