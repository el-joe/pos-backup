<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Core HR & Administration
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('departments');
            $table->foreignId('manager_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('manager_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('base_salary_range')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();

            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('team_id')->nullable()->constrained('teams');
            $table->foreignId('designation_id')->nullable()->constrained('designations');
            $table->foreignId('manager_id')->nullable()->constrained('employees');

            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('status')->default('active'); // active,suspended,terminated

            // Personal / banking basics (extend later)
            $table->string('national_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_account_number')->nullable();

            $table->string('password');
            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('employees')->nullOnDelete();
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('employees')->nullOnDelete();
        });

        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('type'); // contract,id,passport,nda,certificate
            $table->string('title')->nullable();
            $table->string('file_path');
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('asset_type'); // laptop,phone,vehicle,key
            $table->string('asset_tag')->nullable();
            $table->string('description')->nullable();
            $table->date('assigned_at')->nullable();
            $table->date('returned_at')->nullable();
            $table->string('status')->default('assigned'); // assigned,returned,lost
            $table->timestamps();
            $table->softDeletes();
        });

        // 2) Time, Attendance & Payroll
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('yearly_allowance', 10, 2)->default(0);
            $table->boolean('is_paid')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leave_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->date('entry_date');
            $table->decimal('days', 10, 2); // +credit / -debit
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('days', 10, 2)->default(0);
            $table->string('reason')->nullable();
            $table->string('status')->default('pending'); // pending,approved,rejected,cancelled
            $table->foreignId('approved_by')->nullable()->constrained('employees');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('attendance_sheets', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('status')->default('draft'); // draft,submitted,approved
            $table->foreignId('approved_by')->nullable()->constrained('employees');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_sheet_id')->constrained('attendance_sheets')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees');
            $table->timestamp('clock_in_at')->nullable();
            $table->timestamp('clock_out_at')->nullable();
            $table->string('status')->default('present'); // present,absent,late
            $table->string('source')->nullable(); // manual,biometric,api
            $table->timestamps();

            $table->unique(['attendance_sheet_id', 'employee_id']);
        });

        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->string('status')->default('draft'); // draft,approved,paid
            $table->decimal('total_payout', 15, 2)->default(0);
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();

            $table->unique(['month', 'year']);
        });

        Schema::create('payroll_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained('payroll_runs')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('gross_pay', 15, 2)->default(0);
            $table->decimal('net_pay', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['payroll_run_id', 'employee_id']);
        });

        Schema::create('payroll_slip_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_slip_id')->constrained('payroll_slips')->cascadeOnDelete();
            $table->string('type'); // earning,deduction
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('expense_claim_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('claim_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status')->default('submitted'); // submitted,approved,rejected,paid
            $table->foreignId('approved_by')->nullable()->constrained('employees');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();
        });

        Schema::create('expense_claim_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_claim_id')->constrained('expense_claims')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('expense_claim_categories');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->timestamps();
        });

        // 3) Talent Acquisition & Management
        Schema::create('job_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('status')->default('draft'); // draft,approved,open,closed
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('training_courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('provider')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('performance_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('draft'); // draft,active,closed
            $table->timestamps();
            $table->softDeletes();
        });

        // 4) ESS / MSS & Workflows
        Schema::create('onboarding_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('task');
            $table->string('status')->default('pending'); // pending,done
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('offboarding_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('separation_date')->nullable();
            $table->string('reason')->nullable();
            $table->string('status')->default('draft'); // draft,processing,completed
            $table->timestamps();
        });

        Schema::create('hr_approval_requests', function (Blueprint $table) {
            $table->id();
            $table->string('module'); // leave,claim,attendance,payroll,...
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');
            $table->foreignId('requested_by')->nullable()->constrained('employees');
            $table->foreignId('assigned_to')->nullable()->constrained('employees');
            $table->string('status')->default('pending'); // pending,approved,rejected
            $table->timestamp('action_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_approval_requests');
        Schema::dropIfExists('offboarding_records');
        Schema::dropIfExists('onboarding_tasks');

        Schema::dropIfExists('performance_cycles');
        Schema::dropIfExists('training_courses');
        Schema::dropIfExists('job_requisitions');

        Schema::dropIfExists('expense_claim_lines');
        Schema::dropIfExists('expense_claims');
        Schema::dropIfExists('expense_claim_categories');
        Schema::dropIfExists('payroll_slip_lines');
        Schema::dropIfExists('payroll_slips');
        Schema::dropIfExists('payroll_runs');
        Schema::dropIfExists('attendance_logs');
        Schema::dropIfExists('attendance_sheets');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_ledger_entries');
        Schema::dropIfExists('leave_types');

        Schema::dropIfExists('employee_assets');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_contracts');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('designations');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('departments');
    }
};
