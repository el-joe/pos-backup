<?php

namespace App\Services\Hrm;

use App\Enums\PayrollSlipLineTypeEnum;
use App\Models\Tenant\Employee;
use App\Models\Tenant\EmployeeContract;
use App\Models\Tenant\PayrollComponent;
use Carbon\Carbon;

class PayrollCalculationService
{
    /** Fallback divisor when the tenant hasn't configured one. */
    const DEFAULT_WORKING_DAYS_DIVISOR = 26;

    public function __construct(
        private AttendanceResolutionService $attendanceResolutionService = new AttendanceResolutionService(),
        private StatutoryDeductionService $statutoryDeductionService = new StatutoryDeductionService(),
    ) {}

    public function calculateForEmployee(Employee $employee, int $month, int $year): array
    {
        $periodStart = Carbon::create($year, $month)->startOfMonth();
        $periodEnd = Carbon::create($year, $month)->endOfMonth();
        $daysInMonth = $periodStart->daysInMonth;

        $contract = EmployeeContract::where('employee_id', $employee->id)
            ->where('is_active', 1)
            ->where('start_date', '<=', $periodEnd)
            ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', $periodStart))
            ->latest('start_date')
            ->first();

        $basicSalary = (float) ($contract?->basic_salary ?? 0);

        // ---- day classification: single source of truth, each day counted exactly once ----
        $resolvedDays = $this->attendanceResolutionService->resolveForEmployee($employee, $periodStart, $periodEnd);
        $unpaidLeaveDays = $this->attendanceResolutionService->sumBucket($resolvedDays, [AttendanceResolutionService::APPROVED_UNPAID_LEAVE]);
        $absentDays = $this->attendanceResolutionService->sumBucket($resolvedDays, [AttendanceResolutionService::ABSENT]);
        $employedDays = $this->attendanceResolutionService->employedDayFraction($resolvedDays);

        // ---- divisor: configurable per tenant, either a fixed working-days count or
        //      "calendar" basis (divide by the actual number of days in the month) ----
        $basis = tenantSetting('payroll_working_days_basis', 'fixed');
        $divisor = $basis === 'calendar'
            ? $daysInMonth
            : (float) tenantSetting('payroll_working_days_divisor', self::DEFAULT_WORKING_DAYS_DIVISOR);
        $divisor = $divisor > 0 ? $divisor : self::DEFAULT_WORKING_DAYS_DIVISOR;

        $dailyRate = $basicSalary / $divisor;

        // ---- proration for joiners/leavers: pay only for days actually employed within the period ----
        $isFullPeriod = $employedDays >= $daysInMonth;
        $proratedBasicSalary = $isFullPeriod
            ? $basicSalary
            : round($dailyRate * $employedDays, 2);

        $lines = [];
        $gross = 0.0;
        $totalEmployeeDeductions = 0.0;
        $totalEmployerCost = 0.0;

        $basicDescription = $isFullPeriod
            ? 'Basic Salary'
            : "Basic Salary (prorated {$employedDays}/{$daysInMonth} days)";
        $lines[] = ['type' => PayrollSlipLineTypeEnum::BASIC->value, 'amount' => $proratedBasicSalary, 'description' => $basicDescription];
        $gross += $proratedBasicSalary;

        if ($unpaidLeaveDays > 0) {
            $deduction = round($dailyRate * $unpaidLeaveDays, 2);
            $totalEmployeeDeductions += $deduction;
            $lines[] = ['type' => PayrollSlipLineTypeEnum::UNPAID_LEAVE_DEDUCTION->value, 'amount' => -$deduction, 'description' => "Unpaid Leave ({$unpaidLeaveDays} days)"];
        }

        if ($absentDays > 0) {
            $deduction = round($dailyRate * $absentDays, 2);
            $totalEmployeeDeductions += $deduction;
            $lines[] = ['type' => PayrollSlipLineTypeEnum::ABSENCE_DEDUCTION->value, 'amount' => -$deduction, 'description' => "Absent ({$absentDays} days)"];
        }

        // ---- configurable earnings/deductions from payroll_components ----
        $components = PayrollComponent::applicableFor($employee->id, $month, $year)->get();

        foreach ($components as $component) {
            $type = $component->type instanceof PayrollSlipLineTypeEnum ? $component->type : PayrollSlipLineTypeEnum::from($component->type);
            $amount = (float) $component->amount;

            if (in_array($type, PayrollSlipLineTypeEnum::earningTypes(), true)) {
                $gross += $amount;
                $lines[] = ['type' => $type->value, 'amount' => $amount, 'description' => $component->description ?? $type->label()];
            } elseif (in_array($type, PayrollSlipLineTypeEnum::deductionTypes(), true)) {
                $totalEmployeeDeductions += $amount;
                $lines[] = ['type' => $type->value, 'amount' => -$amount, 'description' => $component->description ?? $type->label()];
            }
            // employer-cost-only component types are not expected here; statutory ones are computed below.
        }

        // ---- statutory withholding: both employee and employer sides appear as payslip lines
        //      so gross -> net reconciles (employer contribution never reduces employee net pay) ----
        $socialInsuranceEmployee = $this->statutoryDeductionService->socialInsuranceEmployee($gross);
        $socialInsuranceEmployer = $this->statutoryDeductionService->socialInsuranceEmployer($gross);

        if ($socialInsuranceEmployee > 0) {
            $totalEmployeeDeductions += $socialInsuranceEmployee;
            $lines[] = ['type' => PayrollSlipLineTypeEnum::SOCIAL_INSURANCE_EMPLOYEE->value, 'amount' => -$socialInsuranceEmployee, 'description' => 'Social Insurance (Employee)'];
        }

        if ($socialInsuranceEmployer > 0) {
            $totalEmployerCost += $socialInsuranceEmployer;
            $lines[] = ['type' => PayrollSlipLineTypeEnum::SOCIAL_INSURANCE_EMPLOYER->value, 'amount' => $socialInsuranceEmployer, 'description' => 'Social Insurance (Employer)'];
        }

        $taxableIncome = max(0, $gross - $socialInsuranceEmployee);
        $incomeTax = $this->statutoryDeductionService->incomeTax($taxableIncome);

        if ($incomeTax > 0) {
            $totalEmployeeDeductions += $incomeTax;
            $lines[] = ['type' => PayrollSlipLineTypeEnum::INCOME_TAX->value, 'amount' => -$incomeTax, 'description' => 'Income Tax'];
        }

        $netPay = max(0, round($gross - $totalEmployeeDeductions, 2));

        return [
            'lines' => $lines,
            'gross' => round($gross, 2),
            'netPay' => $netPay,
            // extra figures needed by PayrollRunService::approve() to post the correct
            // gross cost / employer contributions / statutory payables to the ledger.
            'employerCost' => round($totalEmployerCost, 2),
            'socialInsuranceEmployee' => $socialInsuranceEmployee,
            'socialInsuranceEmployer' => $socialInsuranceEmployer,
            'incomeTax' => $incomeTax,
        ];
    }
}
