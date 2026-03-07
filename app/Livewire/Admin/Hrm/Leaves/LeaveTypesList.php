<?php

namespace App\Livewire\Admin\Hrm\Leaves;

use App\Services\Hrm\LeaveTypeService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveTypesList extends Component
{
    use LivewireOperations, WithPagination;

    private LeaveTypeService $leaveTypeService;

    public array $filters = [];
    public bool $collapseFilters = false;
    public $current;

    public function boot(): void
    {
        $this->leaveTypeService = app(LeaveTypeService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->leaveTypeService->find($id);
    }

    public function deleteAlert($id): void
    {
        if (!adminCan('hrm_leaves.delete')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', __('general.messages.are_you_sure'), __('general.messages.hrm.confirm_delete_leave_type'), __('general.messages.yes_delete_it'));
    }

    public function delete(): void
    {
        if (!adminCan('hrm_leaves.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.leave_type_not_found'));
            return;
        }
        $this->leaveTypeService->delete($this->current->id);
        $this->popup('success', __('general.messages.hrm.leave_type_deleted_successfully'));
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->resetPage();
    }

    #[On('re-render')]
    public function render()
    {
        $types = $this->leaveTypeService->list([], $this->filters, 10, 'id');

        return layoutView('hrm.leaves.leave-types-list', get_defined_vars())
            ->title(__('general.titles.hrm_leave_types'));
    }
}
