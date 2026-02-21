<?php

namespace App\Livewire\Admin\Hrm;

use App\Services\Hrm\DepartmentService;
use App\Services\Hrm\DesignationService;
use App\Services\Hrm\EmployeeContractService;
use App\Services\Hrm\EmployeeService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;

class MasterDataPage extends Component
{
    use LivewireOperations;

    private DepartmentService $departmentService;
    private DesignationService $designationService;
    private EmployeeService $employeeService;
    private EmployeeContractService $employeeContractService;

    public bool $collapseFilters = false;
    public array $filters = [];

    public $currentDepartment;
    public $currentDesignation;
    public $currentEmployee;

    public function boot(): void
    {
        $this->departmentService = app(DepartmentService::class);
        $this->designationService = app(DesignationService::class);
        $this->employeeService = app(EmployeeService::class);
        $this->employeeContractService = app(EmployeeContractService::class);
    }

    public function deleteDepartmentAlert(int $id): void
    {
        $this->currentDepartment = $this->departmentService->find($id);
        $this->confirm('deleteDepartment', 'warning', 'Are you sure?', 'You want to delete this department', 'Yes, delete it!');
    }

    public function deleteDepartment(): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        if (!$this->currentDepartment) {
            $this->popup('error', 'Department not found');
            return;
        }
        $this->departmentService->delete($this->currentDepartment->id);
        $this->popup('success', 'Department deleted successfully');
        $this->dismiss();
        $this->reset('currentDepartment');
    }

    public function deleteDesignationAlert(int $id): void
    {
        $this->currentDesignation = $this->designationService->find($id);
        $this->confirm('deleteDesignation', 'warning', 'Are you sure?', 'You want to delete this designation', 'Yes, delete it!');
    }

    public function deleteDesignation(): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        if (!$this->currentDesignation) {
            $this->popup('error', 'Designation not found');
            return;
        }
        $this->designationService->delete($this->currentDesignation->id);
        $this->popup('success', 'Designation deleted successfully');
        $this->dismiss();
        $this->reset('currentDesignation');
    }

    public function deleteEmployeeAlert(int $id): void
    {
        $this->currentEmployee = $this->employeeService->find($id);
        $this->confirm('deleteEmployee', 'warning', 'Are you sure?', 'You want to delete this employee', 'Yes, delete it!');
    }

    public function deleteEmployee(): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        if (!$this->currentEmployee) {
            $this->popup('error', 'Employee not found');
            return;
        }
        $this->employeeService->delete($this->currentEmployee->id);
        $this->popup('success', 'Employee deleted successfully');
        $this->dismiss();
        $this->reset('currentEmployee');
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->dispatch('re-render');
    }

    #[On('re-render')]
    public function render()
    {
        $departments = $this->departmentService->list(
            [],
            ['search' => $this->filters['departments_search'] ?? null],
            null,
            'id'
        );

        $designations = $this->designationService->list(
            ['department'],
            ['search' => $this->filters['designations_search'] ?? null],
            null,
            'id'
        );

        $employees = $this->employeeService->list(
            ['department', 'designation'],
            ['search' => $this->filters['employees_search'] ?? null],
            null,
            'id'
        )->take(100);

        $contracts = $this->employeeContractService->list(['employee'], [], null, 'id')->take(100);

        return layoutView('hrm.master-data', get_defined_vars())
            ->title('HRM Master Data');
    }
}
