<?php

namespace App\Livewire\Admin\Hrm\Leaves;

use App\Enums\LeaveRequestStatusEnum;
use App\Services\Hrm\EmployeeService;
use App\Services\Hrm\LeaveRequestService;
use App\Traits\LivewireOperations;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveRequestsList extends Component
{
    use LivewireOperations, WithPagination;

    private LeaveRequestService $leaveRequestService;
    private EmployeeService $employeeService;

    public array $filters = [
        'status' => 'all',
        'search' => null,
        'employee_id' => null,
    ];

    public bool $collapseFilters = false;
    public $current;

    public function boot(): void
    {
        $this->leaveRequestService = app(LeaveRequestService::class);
        $this->employeeService = app(EmployeeService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->leaveRequestService->find($id, ['employee', 'leaveType']);
    }

    public function approveAlert($id): void
    {
        if (!adminCan('hrm_leaves.approve')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('approve', 'question', __('general.pages.hrm.approve_action'), __('general.messages.hrm.confirm_approve_leave_request'), __('general.pages.hrm.approve_action'));
    }

    public function rejectAlert($id): void
    {
        if (!adminCan('hrm_leaves.reject')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('reject', 'warning', __('general.pages.hrm.statuses.rejected'), __('general.messages.hrm.confirm_reject_leave_request'), __('general.pages.hrm.statuses.rejected'));
    }

    public function approve(): void
    {
        if (!adminCan('hrm_leaves.approve')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.leave_request_not_found'));
            return;
        }

        $this->leaveRequestService->update($this->current->id, [
            'status' => LeaveRequestStatusEnum::APPROVED->value,
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', __('general.messages.hrm.leave_request_approved'));
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function reject(): void
    {
        if (!adminCan('hrm_leaves.reject')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.leave_request_not_found'));
            return;
        }

        $this->leaveRequestService->update($this->current->id, [
            'status' => LeaveRequestStatusEnum::REJECTED->value,
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', __('general.messages.hrm.leave_request_rejected'));
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'status' => 'all',
            'search' => null,
            'employee_id' => null,
        ];
        $this->resetPage();
    }

    #[On('re-render')]
    public function render()
    {
        $requests = $this->leaveRequestService->list(['employee', 'leaveType'], $this->filters, 10, 'id');
        $employees = $this->employeeService->list([], [], null, 'name');

        return layoutView('hrm.leaves.leave-requests-list', get_defined_vars())
            ->title(__('general.titles.hrm_leave_requests'));
    }
}
