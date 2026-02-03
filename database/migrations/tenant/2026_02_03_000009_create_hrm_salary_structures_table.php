<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('basic_salary', 12, 2);
            $table->json('allowances')->nullable(); // [{name, amount, type: fixed/percentage}]
            $table->json('deductions')->nullable(); // [{name, amount, type: fixed/percentage}]
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('net_salary', 12, 2);
            $table->string('currency', 3)->default('EGP');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};
