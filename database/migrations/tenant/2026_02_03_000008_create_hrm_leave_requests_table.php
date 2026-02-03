<?php

use App\Enums\LeaveStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 8, 2);
            $table->boolean('is_half_day')->default(false);
            $table->enum('half_day_period', ['first_half', 'second_half'])->nullable();
            $table->text('reason');
            $table->string('attachment')->nullable();
            $table->string('status')->default(LeaveStatusEnum::PENDING->value);
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->text('approved_remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
