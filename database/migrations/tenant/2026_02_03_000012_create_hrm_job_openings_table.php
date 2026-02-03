<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('ar_job_title')->nullable();
            $table->string('job_code')->unique();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description');
            $table->text('ar_description')->nullable();
            $table->text('requirements');
            $table->text('ar_requirements')->nullable();
            $table->integer('vacancies')->default(1);
            $table->string('employment_type');
            $table->decimal('min_salary', 12, 2)->nullable();
            $table->decimal('max_salary', 12, 2)->nullable();
            $table->string('experience_required')->nullable(); // e.g., "2-5 years"
            $table->string('education_required')->nullable();
            $table->date('opening_date');
            $table->date('closing_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_published')->default(false);
            $table->foreignId('hiring_manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_openings');
    }
};
