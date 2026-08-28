<?php

namespace App\Services\Hrm;

use App\Enums\AttendanceSheetStatusEnum;
use App\Enums\EmployeeStatusEnum;
use App\Enums\LeaveRequestStatusEnum;
use App\Enums\PayrollSlipLineTypeEnum;
use App\Models\Tenant\AttendanceLog;
use App\Models\Tenant\AttendanceSheet;
use App\Models\Tenant\Employee;
use App\Models\Tenant\EmployeeContract;
use App\Models\Tenant\LeaveRequest;
use Carbon\Carbon;

class PayrollCalculationService
{
    const WORKING_DAYS_PER_MONTH = 26;

    public function calculateForEmployee(Employee $employee, int $month, int $year): array
    {
        $periodStart = Carbon::create($year, $month)->startOfMonth();
        $periodEnd = Carbon::create($year, $month)->endOfMonth();

        $contract = EmployeeContract::where('employee_id', $employee->id)
            ->where('is_active', 1)
            ->where('start_date', '<=', $periodEnd)
            ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', $periodStart))
            ->latest('start_date')
            ->first();

        $basicSalary = (float) ($contract?->basic_salary ?? 0);
        $dailyRate = $basicSalary / self::WORKING_DAYS_PER_MONTH;

        $unpaidLeaveDays = $this->countUnpaidLeaveDays($employee->id, $periodStart, $periodEnd);
        $absentDays = $this->countAbsentDays($employee->id, $periodStart, $periodEnd);

        $lines = [];
        $gross = $basicSalary;
        $totalDeductions = 0;

        $lines[] = ['type' => PayrollSlipLineTypeEnum::BASIC->value, 'amount' => $basicSalary, 'description' => 'Basic Salary'];

        if ($unpaidLeaveDays > 0) {
            $deduction = round($dailyRate * $unpaidLeaveDays, 2);
            $totalDeductions += $deduction;
            $lines[] = ['type' => PayrollSlipLineTypeEnum::UNPAID_LEAVE_DEDUCTION->value, 'amount' => -$deduction, 'description' => "Unpaid Leave ({$unpaidLeaveDays} days)"];
        }

        if ($absentDays > 0) {
            $deduction = round($dailyRate * $absentDays, 2);
            $totalDeductions += $deduction;
            $lines[] = ['type' => PayrollSlipLineTypeEnum::ABSENCE_DEDUCTION->value, 'amount' => -$deduction, 'description' => "Absent ({$absentDays} days)"];
        }

        $netPay = max(0, $gross - $totalDeductions);

        return compact('lines', 'gross', 'netPay');
    }

    private function countUnpaidLeaveDays(int $employeeId, Carbon $periodStart, Carbon $periodEnd): float
    {
        return LeaveRequest::where('employee_id', $employeeId)
            ->where('status', LeaveRequestStatusEnum::APPROVED->value)
            ->whereHas('leaveType', fn($q) => $q->where('is_paid', 0))
            ->where('start_date', '<=', $periodEnd)
            ->where('end_date', '>=', $periodStart)
            ->get()
            ->sum(fn($lr) => $this->daysInPeriod($lr->start_date, $lr->end_date, $periodStart, $periodEnd));
    }

    private function countAbsentDays(int $employeeId, Carbon $periodStart, Carbon $periodEnd): int
    {
        $sheetIds = AttendanceSheet::where('status', AttendanceSheetStatusEnum::APPROVED->value)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->pluck('id');

        $presentCount = AttendanceLog::where('employee_id', $employeeId)
            ->whereIn('attendance_sheet_id', $sheetIds)
            ->distinct('attendance_sheet_id')
            ->count('attendance_sheet_id');

        return max(0, $sheetIds->count() - $presentCount);
    }

    private function daysInPeriod($start, $end, Carbon $periodStart, Carbon $periodEnd): float
    {
        $effectiveStart = Carbon::parse($start)->max($periodStart);
        $effectiveEnd = Carbon::parse($end)->min($periodEnd);

        return max(0, $effectiveStart->diffInDays($effectiveEnd) + 1);
    }
}
