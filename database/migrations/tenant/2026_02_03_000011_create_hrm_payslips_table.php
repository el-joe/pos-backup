<?php

use App\Enums\PayslipStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->string('payslip_number')->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_salary_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->tinyInteger('month'); // 1-12
            $table->date('payment_date')->nullable();
            
            // Salary Components
            $table->decimal('basic_salary', 12, 2);
            $table->json('allowances')->nullable();
            $table->decimal('total_allowances', 12, 2)->default(0);
            $table->json('deductions')->nullable();
            $table->decimal('total_deductions', 12, 2)->default(0);
            
            // Attendance Based
            $table->integer('working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('leave_days')->default(0);
            $table->integer('holidays')->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('overtime_amount', 12, 2)->default(0);
            
            // Final Amounts
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('net_salary', 12, 2);
            
            $table->string('status')->default(PayslipStatusEnum::DRAFT->value);
            $table->text('remarks')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['employee_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
