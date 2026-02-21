<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();

            $table->string('code')->unique();
            $table->string('name');

            $table->date('purchase_date')->nullable();
            $table->decimal('cost', 14, 2)->default(0);
            $table->decimal('salvage_value', 14, 2)->default(0);

            $table->unsignedInteger('useful_life_months')->default(0);
            $table->string('depreciation_method')->default('straight_line');
            $table->date('depreciation_start_date')->nullable();

            $table->string('status')->default('active'); // active, disposed, sold
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
