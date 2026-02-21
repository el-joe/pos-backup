<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_asset_extensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixed_asset_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->decimal('amount', 14, 2)->default(0);
            $table->unsignedInteger('added_useful_life_months')->default(0);
            $table->date('extension_date');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_asset_extensions');
    }
};
