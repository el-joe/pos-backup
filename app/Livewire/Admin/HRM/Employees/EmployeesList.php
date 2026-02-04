<?php

namespace App\Livewire\Admin\HRM\Employees;

use App\Enums\EmployeeStatusEnum;
use App\Enums\EmploymentTypeEnum;
use App\Models\Tenant\Employee;
use App\Models\Tenant\Department;
use App\Models\Tenant\Designation;
use App\Models\Tenant\Branch;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class EmployeesList extends Component
{
    use WithPagination, WithFileUploads;

    public $collapseFilters = false;

    public $search = '';
    public $department_filter = '';
    public $designation_filter = '';
    public $branch_filter = '';
    public $status_filter = '';
    public $employment_type_filter = '';
    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'department_filter' => ['except' => ''],
        'designation_filter' => ['except' => ''],
        'branch_filter' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'employment_type_filter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(
            'search',
            'department_filter',
            'designation_filter',
            'branch_filter',
            'status_filter',
            'employment_type_filter'
        );
        $this->resetPage();
    }

    public function render()
    {
        $employees = Employee::query()
            ->with(['department', 'designation', 'branch', 'manager'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('employee_code', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->department_filter, fn($q) => $q->where('department_id', $this->department_filter))
            ->when($this->designation_filter, fn($q) => $q->where('designation_id', $this->designation_filter))
            ->when($this->branch_filter, fn($q) => $q->where('branch_id', $this->branch_filter))
            ->when($this->status_filter, fn($q) => $q->where('status', $this->status_filter))
            ->when($this->employment_type_filter, fn($q) => $q->where('employment_type', $this->employment_type_filter))
            ->latest()
            ->paginate($this->perPage);

        $departments = Department::where('active', true)->get();
        $designations = Designation::where('active', true)->get();
        $branches = Branch::where('active', true)->get();
        $statuses = EmployeeStatusEnum::cases();
        $employment_types = EmploymentTypeEnum::cases();

        return layoutView('hrm.employees.employees-list', get_defined_vars())
            ->title(__('hrm.employees'));
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        session()->flash('success', __('hrm.employee_deleted_successfully'));
    }
}
