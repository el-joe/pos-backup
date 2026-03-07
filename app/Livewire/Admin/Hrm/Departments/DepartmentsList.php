<?php

namespace App\Livewire\Admin\Hrm\Departments;

use App\Services\Hrm\DepartmentService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentsList extends Component
{
    use LivewireOperations, WithPagination;

    private DepartmentService $departmentService;

    public array $filters = [];
    public bool $collapseFilters = false;

    public $current;

    public function boot(): void
    {
        $this->departmentService = app(DepartmentService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->departmentService->find($id);
    }

    public function deleteAlert($id): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', __('general.messages.are_you_sure'), __('general.messages.hrm.confirm_delete_department'), __('general.messages.yes_delete_it'));
    }

    public function delete(): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.department_not_found'));
            return;
        }

        $this->departmentService->delete($this->current->id);
        $this->popup('success', __('general.messages.hrm.department_deleted_successfully'));
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
        $departments = $this->departmentService->list([], $this->filters, 10, 'id');

        return layoutView('hrm.departments.departments-list', get_defined_vars())
            ->title(__('general.titles.hrm_departments'));
    }
}
