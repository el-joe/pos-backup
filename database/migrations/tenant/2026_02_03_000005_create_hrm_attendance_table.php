<?php

use App\Enums\AttendanceStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->integer('working_hours')->nullable(); // in minutes
            $table->integer('overtime_hours')->nullable(); // in minutes
            $table->integer('late_minutes')->nullable();
            $table->integer('early_leaving_minutes')->nullable();
            $table->string('status')->default(AttendanceStatusEnum::PRESENT->value);
            $table->text('remarks')->nullable();
            $table->string('clock_in_location')->nullable();
            $table->string('clock_out_location')->nullable();
            $table->string('clock_in_ip')->nullable();
            $table->string('clock_out_ip')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
