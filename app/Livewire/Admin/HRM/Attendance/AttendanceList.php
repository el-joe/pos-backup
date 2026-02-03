<?php

namespace App\Livewire\Admin\HRM\Attendance;

use App\Enums\AttendanceStatusEnum;
use App\Models\Tenant\Attendance;
use App\Models\Tenant\Employee;
use App\Models\Tenant\Branch;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceList extends Component
{
    use WithPagination;

    public $date_from;
    public $date_to;
    public $employee_filter = '';
    public $branch_filter = '';
    public $status_filter = '';
    public $search = '';

    public function mount()
    {
        $this->date_from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->date_to = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $attendances = Attendance::query()
            ->with(['employee', 'branch'])
            ->when($this->date_from, fn($q) => $q->whereDate('date', '>=', $this->date_from))
            ->when($this->date_to, fn($q) => $q->whereDate('date', '<=', $this->date_to))
            ->when($this->employee_filter, fn($q) => $q->where('employee_id', $this->employee_filter))
            ->when($this->branch_filter, fn($q) => $q->where('branch_id', $this->branch_filter))
            ->when($this->status_filter, fn($q) => $q->where('status', $this->status_filter))
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('date')
            ->paginate(25);

        return view('livewire.admin.hrm.attendance.attendance-list', [
            'attendances' => $attendances,
            'employees' => Employee::where('status', 'active')->get(),
            'branches' => Branch::where('active', true)->get(),
            'statuses' => AttendanceStatusEnum::cases(),
        ]);
    }
}
