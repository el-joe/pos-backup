<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('measurement_unit')->nullable(); // percentage, number, rating
            $table->decimal('target_value', 12, 2)->nullable();
            $table->integer('weightage')->default(0); // percentage weight in overall performance
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_kpis');
    }
};
