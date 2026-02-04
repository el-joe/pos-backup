<?php

namespace App\Livewire\Admin\HRM\Attendance;

use App\Models\Tenant\Attendance;
use App\Models\Tenant\Employee;
use Carbon\Carbon;
use Livewire\Component;

class ClockInOut extends Component
{
    public $employee_id;
    public $today_attendance;

    public function mount()
    {
        // Get current logged-in user's employee record
        $this->employee_id = auth()->user()->employee?->id;
        $this->loadTodayAttendance();
    }

    public function loadTodayAttendance()
    {
        if ($this->employee_id) {
            $this->today_attendance = Attendance::where('employee_id', $this->employee_id)
                ->whereDate('date', Carbon::today())
                ->first();
        }
    }

    public function clockIn()
    {
        if (!$this->employee_id) {
            session()->flash('error', __('hrm.no_employee_record_found'));
            return;
        }

        if ($this->today_attendance && $this->today_attendance->clock_in) {
            session()->flash('error', __('hrm.already_clocked_in'));
            return;
        }

        Attendance::create([
            'employee_id' => $this->employee_id,
            'branch_id' => auth()->user()->branch_id,
            'date' => Carbon::today(),
            'clock_in' => Carbon::now(),
            'clock_in_ip' => request()->ip(),
            'status' => 'present',
        ]);

        session()->flash('success', __('hrm.clocked_in_successfully'));
        $this->loadTodayAttendance();
    }

    public function clockOut()
    {
        if (!$this->today_attendance || !$this->today_attendance->clock_in) {
            session()->flash('error', __('hrm.please_clock_in_first'));
            return;
        }

        if ($this->today_attendance->clock_out) {
            session()->flash('error', __('hrm.already_clocked_out'));
            return;
        }

        $clockIn = Carbon::parse($this->today_attendance->clock_in);
        $clockOut = Carbon::now();
        $workingMinutes = $clockOut->diffInMinutes($clockIn);

        $this->today_attendance->update([
            'clock_out' => $clockOut,
            'clock_out_ip' => request()->ip(),
            'working_hours' => $workingMinutes,
        ]);

        session()->flash('success', __('hrm.clocked_out_successfully'));
        $this->loadTodayAttendance();
    }

    public function render()
    {
        return layoutView('hrm.attendance.clock-in-out', get_defined_vars())
            ->title(__('hrm.clock_in'));
    }
}
