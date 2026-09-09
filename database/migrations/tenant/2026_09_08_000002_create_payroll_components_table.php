<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Recurring/one-off pay components per employee (allowance, overtime, bonus, commission,
        // loan repayment, advance, penalty, ...). Driven by App\Enums\PayrollSlipLineTypeEnum.
        // A component with is_recurring=true applies every payroll period until end_date/removed;
        // otherwise it applies only to the given month/year (one-off).
        Schema::create('payroll_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->index();
            $table->string('type'); // PayrollSlipLineTypeEnum value: allowance, overtime, bonus, commission, loan_repayment, advance_deduction, penalty_deduction...
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->boolean('is_recurring')->default(true);
            $table->unsignedTinyInteger('month')->nullable(); // required when is_recurring = false
            $table->unsignedSmallInteger('year')->nullable(); // required when is_recurring = false
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_components');
    }
};
