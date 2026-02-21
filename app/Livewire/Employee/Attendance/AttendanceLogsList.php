<?php

namespace App\Livewire\Employee\Attendance;

use App\Models\Tenant\AttendanceLog;
use App\Models\Tenant\AttendanceSheet;
use App\Traits\LivewireOperations;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceLogsList extends Component
{
    use WithPagination;

    use LivewireOperations;

    protected function todayLastLog()
    {
        $employee = employee();
        if (!$employee) {
            return null;
        }

        return AttendanceLog::query()
            ->with(['sheet'])
            ->where('employee_id', $employee->id)
            ->whereHas('sheet', fn($q) => $q->whereDate('date', Carbon::today()))
            ->orderByDesc('id')
            ->first();
    }

    protected function todayOpenLogForSheet(int $sheetId)
    {
        $employee = employee();
        if (!$employee) {
            return null;
        }

        return AttendanceLog::query()
            ->where('attendance_sheet_id', $sheetId)
            ->where('employee_id', $employee->id)
            ->whereNull('clock_out_at')
            ->orderByDesc('id')
            ->first();
    }

    protected function getOrCreateTodaySheet()
    {
        $employee = employee();
        $today = Carbon::today()->toDateString();

        $sheet = AttendanceSheet::query()
            ->whereDate('date', $today)
            ->when($employee?->department_id, fn($q) => $q->where('department_id', $employee->department_id), fn($q) => $q->whereNull('department_id'))
            ->orderByDesc('id')
            ->first();

        if ($sheet) {
            return $sheet;
        }

        return AttendanceSheet::query()->create([
            'date' => $today,
            'department_id' => $employee?->department_id,
            'status' => 'draft',
        ]);
    }

    public function checkIn(): void
    {
        $employee = employee();
        if (!$employee) {
            abort(403);
        }

        $sheet = $this->getOrCreateTodaySheet();
        if (($sheet->status ?? null) === 'approved') {
            $this->popup('warning', 'Today attendance is already approved');
            return;
        }

        $open = $this->todayOpenLogForSheet($sheet->id);
        if ($open) {
            $this->popup('warning', 'You are already checked in. Please check out first.');
            return;
        }

        try {
            DB::beginTransaction();

            AttendanceLog::query()->create([
                'attendance_sheet_id' => $sheet->id,
                'employee_id' => $employee->id,
                'clock_in_at' => Carbon::now(),
                'clock_out_at' => null,
                'status' => 'present',
                'source' => 'employee',
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Failed to check in: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Checked in successfully');
        $this->dispatch('re-render');
    }

    public function checkOut(): void
    {
        $employee = employee();
        if (!$employee) {
            abort(403);
        }

        $sheet = $this->getOrCreateTodaySheet();
        if (($sheet->status ?? null) === 'approved') {
            $this->popup('warning', 'Today attendance is already approved');
            return;
        }

        $open = $this->todayOpenLogForSheet($sheet->id);
        if (!$open || !$open->clock_in_at) {
            $this->popup('warning', 'You are not currently checked in');
            return;
        }

        try {
            DB::beginTransaction();
            $open->update([
                'clock_out_at' => Carbon::now(),
                'source' => $open->source ?? 'employee',
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Failed to check out: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Checked out successfully');
        $this->dispatch('re-render');
    }

    #[On('re-render')]
    public function render()
    {
        $employee = employee();

        $todayLog = $this->todayLastLog();
        $todaySheet = $this->getOrCreateTodaySheet();
        $todayOpenLog = $this->todayOpenLogForSheet($todaySheet->id);

        $logs = AttendanceLog::query()
            ->with(['sheet'])
            ->where('employee_id', $employee->id)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return employeeLayoutView('employee.attendance.attendance-logs-list', get_defined_vars())
            ->title('My Attendance');
    }
}
