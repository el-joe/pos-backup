<?php

namespace App\Services\HRM;

use App\Models\Tenant\Employee;
use App\Models\Tenant\EmployeeLeaveEntitlement;
use App\Models\Tenant\LeaveType;
use Carbon\Carbon;

class LeaveEntitlementService
{
    /**
     * Initialize leave entitlements for a new employee
     */
    public function initializeLeaveEntitlements(Employee $employee, int $year = null): void
    {
        $year = $year ?? Carbon::now()->year;

        $leaveTypes = LeaveType::where('active', true)->get();

        foreach ($leaveTypes as $leaveType) {
            // Calculate pro-rated leave days if joined mid-year
            $totalDays = $this->calculateProRatedLeaveDays(
                $employee->joining_date,
                $leaveType->days_per_year,
                $year
            );

            EmployeeLeaveEntitlement::create([
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'year' => $year,
                'total_days' => $totalDays,
                'used_days' => 0,
                'pending_days' => 0,
                'remaining_days' => $totalDays,
                'carried_forward_days' => 0,
            ]);
        }
    }

    /**
     * Calculate pro-rated leave days based on joining date
     */
    private function calculateProRatedLeaveDays($joiningDate, $annualDays, $year): float
    {
        if (!$joiningDate) {
            return $annualDays;
        }

        $joining = Carbon::parse($joiningDate);
        $yearStart = Carbon::create($year, 1, 1);
        $yearEnd = Carbon::create($year, 12, 31);

        // If joined before or in this year
        if ($joining->year <= $year) {
            $startDate = $joining->year == $year ? $joining : $yearStart;
            $daysInYear = $yearEnd->diffInDays($yearStart) + 1;
            $daysWorked = $yearEnd->diffInDays($startDate) + 1;

            return round(($annualDays * $daysWorked) / $daysInYear, 2);
        }

        return 0;
    }

    /**
     * Process leave carry forward for the new year
     */
    public function processCarryForward(Employee $employee, int $fromYear, int $toYear): void
    {
        $previousEntitlements = EmployeeLeaveEntitlement::where('employee_id', $employee->id)
            ->where('year', $fromYear)
            ->get();

        foreach ($previousEntitlements as $prev) {
            $leaveType = $prev->leaveType;

            if (!$leaveType->carry_forward) {
                continue;
            }

            // Calculate carry forward amount
            $carryForwardDays = min(
                $prev->remaining_days,
                $leaveType->max_carry_forward_days ?? $prev->remaining_days
            );

            // Create or update entitlement for new year
            $newEntitlement = EmployeeLeaveEntitlement::firstOrNew([
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'year' => $toYear,
            ]);

            $newEntitlement->total_days = $leaveType->days_per_year;
            $newEntitlement->carried_forward_days = $carryForwardDays;
            $newEntitlement->remaining_days = $leaveType->days_per_year + $carryForwardDays;
            $newEntitlement->used_days = 0;
            $newEntitlement->pending_days = 0;
            $newEntitlement->save();
        }
    }

    /**
     * Check if employee has sufficient leave balance
     */
    public function hasLeaveBalance(Employee $employee, int $leaveTypeId, float $days, int $year): bool
    {
        $entitlement = EmployeeLeaveEntitlement::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();

        if (!$entitlement) {
            return false;
        }

        return $entitlement->remaining_days >= $days;
    }

    /**
     * Deduct leave days from entitlement
     */
    public function deductLeave(Employee $employee, int $leaveTypeId, float $days, int $year): void
    {
        $entitlement = EmployeeLeaveEntitlement::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();

        if ($entitlement) {
            $entitlement->used_days += $days;
            $entitlement->remaining_days -= $days;
            $entitlement->save();
        }
    }

    /**
     * Add leave days back to entitlement (e.g., when leave is cancelled)
     */
    public function creditLeave(Employee $employee, int $leaveTypeId, float $days, int $year): void
    {
        $entitlement = EmployeeLeaveEntitlement::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();

        if ($entitlement) {
            $entitlement->used_days -= $days;
            $entitlement->remaining_days += $days;
            $entitlement->save();
        }
    }
}
