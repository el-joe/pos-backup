<?php

namespace App\Services\HRM;

use App\Models\Tenant\Employee;
use App\Models\Tenant\Attendance;
use App\Models\Tenant\Payslip;
use App\Models\Tenant\EmployeeSalary;
use App\Enums\PayslipStatusEnum;
use Carbon\Carbon;

class PayrollService
{
    /**
     * Generate payslip for a single employee
     */
    public function generatePayslip(Employee $employee, int $year, int $month): ?Payslip
    {
        // Check if payslip already exists
        $existing = Payslip::where('employee_id', $employee->id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Get current salary
        $salary = $employee->currentSalary()->first();
        if (!$salary) {
            return null;
        }

        // Calculate attendance metrics
        $attendanceData = $this->calculateAttendance($employee, $year, $month);

        // Calculate overtime
        $overtime = $this->calculateOvertime($employee, $year, $month);

        // Prepare allowances and deductions
        $allowances = $salary->allowances ?? [];
        $deductions = $salary->deductions ?? [];

        $totalAllowances = collect($allowances)->sum('amount');
        $totalDeductions = collect($deductions)->sum('amount');

        // Calculate absence deductions
        $absenceDeduction = $this->calculateAbsenceDeduction(
            $salary->basic_salary,
            $attendanceData['working_days'],
            $attendanceData['absent_days']
        );

        // Add absence deduction to total deductions
        if ($absenceDeduction > 0) {
            $deductions[] = [
                'name' => 'Absence Deduction',
                'ar_name' => 'خصم الغياب',
                'amount' => $absenceDeduction,
                'type' => 'fixed'
            ];
            $totalDeductions += $absenceDeduction;
        }

        // Calculate final amounts
        $grossSalary = $salary->basic_salary + $totalAllowances + $overtime['amount'];
        $netSalary = $grossSalary - $totalDeductions;

        // Create payslip
        return Payslip::create([
            'employee_id' => $employee->id,
            'employee_salary_id' => $salary->id,
            'year' => $year,
            'month' => $month,
            'basic_salary' => $salary->basic_salary,
            'allowances' => $allowances,
            'total_allowances' => $totalAllowances,
            'deductions' => $deductions,
            'total_deductions' => $totalDeductions,
            'working_days' => $attendanceData['working_days'],
            'present_days' => $attendanceData['present_days'],
            'absent_days' => $attendanceData['absent_days'],
            'leave_days' => $attendanceData['leave_days'],
            'holidays' => $attendanceData['holidays'],
            'overtime_hours' => $overtime['hours'],
            'overtime_amount' => $overtime['amount'],
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'status' => PayslipStatusEnum::GENERATED,
            'generated_by' => auth()->id(),
        ]);
    }

    /**
     * Calculate attendance metrics for the month
     */
    private function calculateAttendance(Employee $employee, int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $workingDays = $startDate->daysInMonth;
        $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $leaveDays = $attendances->where('status', 'on_leave')->count();
        $holidays = $attendances->where('status', 'holiday')->count();

        return [
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'leave_days' => $leaveDays,
            'holidays' => $holidays,
        ];
    }

    /**
     * Calculate overtime hours and amount
     */
    private function calculateOvertime(Employee $employee, int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $totalOvertimeMinutes = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('overtime_hours');

        $overtimeHours = $totalOvertimeMinutes / 60;

        // Get salary to calculate hourly rate
        $salary = $employee->currentSalary()->first();
        $hourlyRate = $salary ? ($salary->basic_salary / 30 / 8) : 0; // Assuming 30 days, 8 hours per day

        // Overtime is typically paid at 1.5x or 2x
        $overtimeRate = $hourlyRate * 1.5;
        $overtimeAmount = $overtimeHours * $overtimeRate;

        return [
            'hours' => round($overtimeHours, 2),
            'amount' => round($overtimeAmount, 2),
        ];
    }

    /**
     * Calculate deduction for absent days
     */
    private function calculateAbsenceDeduction(float $basicSalary, int $workingDays, int $absentDays): float
    {
        if ($absentDays <= 0) {
            return 0;
        }

        $dailyRate = $basicSalary / $workingDays;
        return round($dailyRate * $absentDays, 2);
    }

    /**
     * Approve payslip
     */
    public function approvePayslip(Payslip $payslip): void
    {
        $payslip->update([
            'status' => PayslipStatusEnum::SENT,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Mark payslip as paid and create accounting entry
     */
    public function markAsPaid(Payslip $payslip, int $accountId): void
    {
        $payslip->update([
            'status' => PayslipStatusEnum::PAID,
            'payment_date' => now(),
            'account_id' => $accountId,
        ]);

        // TODO: Create accounting transaction
        // This should debit Salary Expense and credit Cash/Bank
    }
}
