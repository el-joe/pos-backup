<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name');
            $table->string('ar_program_name')->nullable();
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->string('trainer_name')->nullable();
            $table->string('training_type'); // internal, external, online
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_hours')->nullable();
            $table->string('location')->nullable();
            $table->decimal('cost_per_participant', 12, 2)->nullable();
            $table->integer('max_participants')->nullable();
            $table->text('objectives')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_programs');
    }
};
