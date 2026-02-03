<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('salary_structure_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('basic_salary', 12, 2);
            $table->json('allowances')->nullable();
            $table->json('deductions')->nullable();
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('net_salary', 12, 2);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_current')->default(true);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};
