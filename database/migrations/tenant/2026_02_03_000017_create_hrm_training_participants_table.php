<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['enrolled', 'completed', 'cancelled', 'failed'])->default('enrolled');
            $table->integer('attendance_percentage')->nullable();
            $table->decimal('assessment_score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->string('certificate_path')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();
            
            $table->unique(['training_program_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_participants');
    }
};
