<?php

namespace App\Livewire\Admin\Hrm\Leaves;

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
        $this->confirm('approve', 'question', 'Approve?', 'You want to approve this leave request', 'Yes, approve');
    }

    public function rejectAlert($id): void
    {
        if (!adminCan('hrm_leaves.reject')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('reject', 'warning', 'Reject?', 'You want to reject this leave request', 'Yes, reject');
    }

    public function approve(): void
    {
        if (!adminCan('hrm_leaves.approve')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Leave request not found');
            return;
        }

        $this->leaveRequestService->update($this->current->id, [
            'status' => 'approved',
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', 'Leave request approved');
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
            $this->popup('error', 'Leave request not found');
            return;
        }

        $this->leaveRequestService->update($this->current->id, [
            'status' => 'rejected',
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', 'Leave request rejected');
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

        return layoutView('hrm.leaves.leave-requests-list', get_defined_vars())
            ->title('HRM Leave Requests');
    }
}
