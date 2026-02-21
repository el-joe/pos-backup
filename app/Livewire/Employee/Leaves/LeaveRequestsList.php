<?php

namespace App\Livewire\Employee\Leaves;

use App\Models\Tenant\LeaveType;
use App\Services\Hrm\LeaveRequestService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveRequestsList extends Component
{
    use WithPagination;

    private LeaveRequestService $leaveRequestService;

    public array $form = [
        'leave_type_id' => null,
        'start_date' => null,
        'end_date' => null,
        'reason' => null,
    ];

    public function boot(): void
    {
        $this->leaveRequestService = app(LeaveRequestService::class);
    }

    public function submit(): void
    {
        $employee = employee();

        $this->validate([
            'form.leave_type_id' => ['required', 'exists:leave_types,id'],
            'form.start_date' => ['required', 'date'],
            'form.end_date' => ['required', 'date', 'after_or_equal:form.start_date'],
            'form.reason' => ['nullable', 'string', 'max:500'],
        ]);

        $start = Carbon::parse($this->form['start_date'])->startOfDay();
        $end = Carbon::parse($this->form['end_date'])->startOfDay();
        $days = $start->diffInDays($end) + 1;

        $this->leaveRequestService->create([
            'employee_id' => $employee->id,
            'leave_type_id' => $this->form['leave_type_id'],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'days' => $days,
            'reason' => $this->form['reason'],
            'status' => 'pending',
        ]);

        $this->reset('form');
        session()->flash('success', 'Leave request submitted');
        $this->resetPage();
    }

    public function render()
    {
        $employee = employee();
        $leaveTypes = LeaveType::query()->orderBy('name')->get();
        $leaveRequests = $this->leaveRequestService->list(['leaveType'], ['employee_id' => $employee->id], 10, 'id');

        return employeeLayoutView('employee.leaves.leave-requests-list', get_defined_vars())
            ->title('My Leave Requests');
    }
}
