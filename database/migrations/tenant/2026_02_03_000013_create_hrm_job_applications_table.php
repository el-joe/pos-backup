<?php

use App\Enums\JobApplicationStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('job_opening_id')->constrained()->cascadeOnDelete();
            
            // Applicant Details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('mobile')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            
            // Professional Details
            $table->string('current_company')->nullable();
            $table->string('current_designation')->nullable();
            $table->decimal('current_salary', 12, 2)->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->integer('total_experience_years')->default(0);
            $table->integer('notice_period_days')->nullable();
            $table->string('highest_qualification')->nullable();
            
            // Application
            $table->text('cover_letter')->nullable();
            $table->string('resume_path')->nullable();
            $table->json('additional_documents')->nullable();
            $table->string('source')->nullable(); // website, referral, linkedin, etc.
            $table->string('referral_name')->nullable();
            
            $table->string('status')->default(JobApplicationStatusEnum::APPLIED->value);
            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->text('interview_notes')->nullable();
            $table->integer('rating')->nullable(); // 1-5
            $table->text('remarks')->nullable();
            
            $table->foreignId('assigned_to')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
