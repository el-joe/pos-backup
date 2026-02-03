<?php

use App\Enums\PerformanceRatingEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_appraisals', function (Blueprint $table) {
            $table->id();
            $table->string('appraisal_number')->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('appraisal_period'); // Q1 2024, 2024, etc.
            $table->date('start_date');
            $table->date('end_date');
            $table->date('appraisal_date');
            
            $table->foreignId('appraiser_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('employees')->nullOnDelete();
            
            $table->json('kpi_scores')->nullable(); // [{kpi_id, target, achieved, score}]
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->string('overall_rating')->nullable();
            
            $table->text('strengths')->nullable();
            $table->text('areas_of_improvement')->nullable();
            $table->text('goals_for_next_period')->nullable();
            $table->text('employee_comments')->nullable();
            $table->text('appraiser_comments')->nullable();
            $table->text('reviewer_comments')->nullable();
            
            $table->boolean('is_submitted')->default(false);
            $table->boolean('is_acknowledged_by_employee')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_appraisals');
    }
};
