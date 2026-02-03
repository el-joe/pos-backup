<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->integer('days_per_year')->default(0);
            $table->boolean('is_paid')->default(true);
            $table->boolean('carry_forward')->default(false);
            $table->integer('max_carry_forward_days')->nullable();
            $table->boolean('requires_document')->default(false);
            $table->integer('max_consecutive_days')->nullable();
            $table->integer('min_days_notice')->default(0);
            $table->string('color')->default('primary');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
