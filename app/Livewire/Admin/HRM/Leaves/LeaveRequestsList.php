<?php

namespace App\Livewire\Admin\HRM\Leaves;

use App\Enums\LeaveStatusEnum;
use App\Models\Tenant\LeaveRequest;
use App\Models\Tenant\LeaveType;
use App\Models\Tenant\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveRequestsList extends Component
{
    use WithPagination;

    public $search = '';
    public $employee_filter = '';
    public $leave_type_filter = '';
    public $status_filter = '';
    public $date_from;
    public $date_to;

    public function approveLeave($id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => LeaveStatusEnum::APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Update leave entitlement
        $entitlement = $leave->employee->leaveEntitlements()
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $leave->start_date->year)
            ->first();

        if ($entitlement) {
            $entitlement->update([
                'used_days' => $entitlement->used_days + $leave->total_days,
                'pending_days' => $entitlement->pending_days - $leave->total_days,
                'remaining_days' => $entitlement->total_days - ($entitlement->used_days + $leave->total_days),
            ]);
        }

        session()->flash('success', __('hrm.leave_approved_successfully'));
    }

    public function rejectLeave($id, $reason = null)
    {
        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => LeaveStatusEnum::REJECTED,
            'rejected_by' => auth()->id(),
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);

        // Update leave entitlement
        $entitlement = $leave->employee->leaveEntitlements()
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $leave->start_date->year)
            ->first();

        if ($entitlement) {
            $entitlement->update([
                'pending_days' => $entitlement->pending_days - $leave->total_days,
            ]);
        }

        session()->flash('success', __('hrm.leave_rejected_successfully'));
    }

    public function render()
    {
        $leaveRequests = LeaveRequest::query()
            ->with(['employee', 'leaveType', 'approver'])
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->employee_filter, fn($q) => $q->where('employee_id', $this->employee_filter))
            ->when($this->leave_type_filter, fn($q) => $q->where('leave_type_id', $this->leave_type_filter))
            ->when($this->status_filter, fn($q) => $q->where('status', $this->status_filter))
            ->when($this->date_from, fn($q) => $q->whereDate('start_date', '>=', $this->date_from))
            ->when($this->date_to, fn($q) => $q->whereDate('end_date', '<=', $this->date_to))
            ->latest()
            ->paginate(25);

        return view('livewire.admin.hrm.leaves.leave-requests-list', [
            'leaveRequests' => $leaveRequests,
            'employees' => Employee::where('status', 'active')->get(),
            'leaveTypes' => LeaveType::where('active', true)->get(),
            'statuses' => LeaveStatusEnum::cases(),
        ]);
    }
}
