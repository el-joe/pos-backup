<?php

namespace App\Services\Hrm;

use App\Enums\AttendanceLogStatusEnum;
use App\Enums\AttendanceSheetStatusEnum;
use App\Enums\LeaveRequestStatusEnum;
use App\Models\Tenant\AttendanceLog;
use App\Models\Tenant\AttendanceSheet;
use App\Models\Tenant\Employee;
use App\Models\Tenant\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * Classifies every calendar day of a payroll period into exactly one bucket
 * per employee, so a day is never deducted (or paid) twice.
 *
 * Buckets: worked, weekly_rest, public_holiday, approved_paid_leave,
 * approved_unpaid_leave, absent, before_hire, after_termination.
 *
 * NOTE: this codebase currently has no weekly-rest-day or public-holiday
 * model (no fields on Employee/Branch for a rest day, no holidays table).
 * Until that exists, days that would otherwise be "weekly_rest"/"public_holiday"
 * degrade to "worked" (best available data: an employee not on leave and not
 * marked absent on an approved sheet is assumed to be a normal working day).
 * This keeps the classifier extensible without inventing schema out of scope.
 */
class AttendanceResolutionService
{
    public const WORKED = 'worked';
    public const WEEKLY_REST = 'weekly_rest';
    public const PUBLIC_HOLIDAY = 'public_holiday';
    public const APPROVED_PAID_LEAVE = 'approved_paid_leave';
    public const APPROVED_UNPAID_LEAVE = 'approved_unpaid_leave';
    public const ABSENT = 'absent';
    public const BEFORE_HIRE = 'before_hire';
    public const AFTER_TERMINATION = 'after_termination';

    /**
     * @return array<string, array{bucket: string, fraction: float}> keyed by Y-m-d date string.
     *         "fraction" is the portion of the day (0..1) attributed to that bucket, so
     *         half-day leave/absence is supported end-to-end without truncation.
     */
    public function resolveForEmployee(Employee $employee, Carbon $periodStart, Carbon $periodEnd): array
    {
        $days = [];
        foreach (CarbonPeriod::create($periodStart->copy()->startOfDay(), $periodEnd->copy()->startOfDay()) as $date) {
            $days[$date->format('Y-m-d')] = ['bucket' => self::WORKED, 'fraction' => 1.0];
        }

        // 1) before_hire / after_termination take priority over everything else.
        $hireDate = $employee->hire_date ? Carbon::parse($employee->hire_date)->startOfDay() : null;
        $terminationDate = $employee->termination_date ? Carbon::parse($employee->termination_date)->startOfDay() : null;

        foreach ($days as $key => $info) {
            $date = Carbon::parse($key);
            if ($hireDate && $date->lt($hireDate)) {
                $days[$key] = ['bucket' => self::BEFORE_HIRE, 'fraction' => 1.0];
            } elseif ($terminationDate && $date->gt($terminationDate)) {
                $days[$key] = ['bucket' => self::AFTER_TERMINATION, 'fraction' => 1.0];
            }
        }

        // 2) approved leave (paid / unpaid) — fractional, based on the leave request's
        //    own `days` value spread evenly across the calendar days it spans.
        $leaveRequests = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', LeaveRequestStatusEnum::APPROVED->value)
            ->where('start_date', '<=', $periodEnd)
            ->where('end_date', '>=', $periodStart)
            ->with('leaveType')
            ->get();

        foreach ($leaveRequests as $leaveRequest) {
            $start = Carbon::parse($leaveRequest->start_date)->startOfDay();
            $end = Carbon::parse($leaveRequest->end_date)->startOfDay();
            $spanDays = max(1, $start->diffInDays($end) + 1);
            $perDayFraction = min(1.0, ((float) $leaveRequest->days) / $spanDays);
            $isPaid = (bool) ($leaveRequest->leaveType?->is_paid ?? true);
            $bucket = $isPaid ? self::APPROVED_PAID_LEAVE : self::APPROVED_UNPAID_LEAVE;

            foreach (CarbonPeriod::create($start, $end) as $date) {
                $key = $date->format('Y-m-d');
                if (!isset($days[$key])) {
                    continue; // outside the requested period
                }
                // before_hire/after_termination still wins (can't be on leave before joining).
                if (in_array($days[$key]['bucket'], [self::BEFORE_HIRE, self::AFTER_TERMINATION], true)) {
                    continue;
                }
                $days[$key] = ['bucket' => $bucket, 'fraction' => $perDayFraction];
            }
        }

        // 3) attendance sheets: an approved sheet with no log (or an explicit "absent" log)
        //    for a day that isn't already leave/before_hire/after_termination means absent.
        $sheetIds = AttendanceSheet::where('status', AttendanceSheetStatusEnum::APPROVED->value)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->pluck('id', 'date');

        // date column may be a Carbon-castable value; normalize to Y-m-d strings.
        $sheetsByDate = [];
        foreach ($sheetIds as $date => $id) {
            $sheetsByDate[Carbon::parse($date)->format('Y-m-d')] = $id;
        }

        if (!empty($sheetsByDate)) {
            $logs = AttendanceLog::where('employee_id', $employee->id)
                ->whereIn('attendance_sheet_id', array_values($sheetsByDate))
                ->get()
                ->keyBy('attendance_sheet_id');

            foreach ($sheetsByDate as $dateKey => $sheetId) {
                if (!isset($days[$dateKey])) {
                    continue;
                }
                if (in_array($days[$dateKey]['bucket'], [
                    self::BEFORE_HIRE, self::AFTER_TERMINATION, self::APPROVED_PAID_LEAVE, self::APPROVED_UNPAID_LEAVE,
                ], true)) {
                    continue; // already accounted for — never double count
                }

                $log = $logs->get($sheetId);
                if (!$log || $log->status?->value === AttendanceLogStatusEnum::ABSENT->value) {
                    $days[$dateKey] = ['bucket' => self::ABSENT, 'fraction' => 1.0];
                } else {
                    $days[$dateKey] = ['bucket' => self::WORKED, 'fraction' => 1.0];
                }
            }
        }

        return $days;
    }

    /**
     * Sum of fractional days in the given buckets, from a resolveForEmployee() result.
     */
    public function sumBucket(array $resolvedDays, array $buckets): float
    {
        $total = 0.0;
        foreach ($resolvedDays as $info) {
            if (in_array($info['bucket'], $buckets, true)) {
                $total += $info['fraction'];
            }
        }
        return round($total, 2);
    }

    /**
     * Number of calendar days in the period the employee was actually employed
     * (not before_hire / after_termination). Used for proration.
     */
    public function employedDayFraction(array $resolvedDays): float
    {
        $total = 0.0;
        foreach ($resolvedDays as $info) {
            if (!in_array($info['bucket'], [self::BEFORE_HIRE, self::AFTER_TERMINATION], true)) {
                $total += 1.0;
            }
        }
        return $total;
    }
}
