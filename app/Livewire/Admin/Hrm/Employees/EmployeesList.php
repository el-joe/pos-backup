<?php

namespace App\Livewire\Admin\Hrm\Employees;

use App\Services\Hrm\EmployeeService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeesList extends Component
{
    use LivewireOperations, WithPagination;

    private EmployeeService $employeeService;

    public array $filters = [];
    public bool $collapseFilters = false;

    public $current;

    public function boot(): void
    {
        $this->employeeService = app(EmployeeService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->employeeService->find($id);
    }

    public function deleteAlert($id): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', __('general.messages.are_you_sure'), __('general.messages.hrm.confirm_delete_employee'), __('general.messages.yes_delete_it'));
    }

    public function delete(): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.employee_not_found'));
            return;
        }

        $this->employeeService->delete($this->current->id);
        $this->popup('success', __('general.messages.hrm.employee_deleted_successfully'));
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
        $employees = $this->employeeService->list(['department', 'designation', 'manager'], $this->filters, 10, 'id');

        return layoutView('hrm.employees.employees-list', get_defined_vars())
            ->title(__('general.titles.hrm_employees'));
    }
}
