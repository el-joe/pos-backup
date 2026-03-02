<?php

namespace App\Livewire\Admin\Hrm\MasterData;

use App\Models\Tenant\Employee;
use App\Services\Hrm\DepartmentService;
use App\Services\Hrm\DesignationService;
use App\Services\Hrm\EmployeeService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class EmployeeModal extends Component
{
    use LivewireOperations;

    public $current;
    public array $data = [
        'employee_code' => null,
        'name' => null,
        'email' => null,
        'phone' => null,
        'department_id' => null,
        'designation_id' => null,
        'manager_id' => null,
        'hire_date' => null,
        'status' => 'active',
        'password' => null,
    ];

    private EmployeeService $employeeService;
    private DepartmentService $departmentService;
    private DesignationService $designationService;

    public function boot(): void
    {
        $this->employeeService = app(EmployeeService::class);
        $this->departmentService = app(DepartmentService::class);
        $this->designationService = app(DesignationService::class);
    }

    #[On('hrm-employee-set-current')]
    public function setCurrent($id = null): void
    {
        $this->current = $this->employeeService->find($id);
        if ($this->current) {
            $this->data = [
                'employee_code' => $this->current->employee_code,
                'name' => $this->current->name,
                'email' => $this->current->email,
                'phone' => $this->current->phone,
                'department_id' => $this->current->department_id,
                'designation_id' => $this->current->designation_id,
                'manager_id' => $this->current->manager_id,
                'hire_date' => optional($this->current->hire_date)->format('Y-m-d'),
                'status' => $this->current->status,
                'password' => null,
            ];
        } else {
            $this->reset('data', 'current');
            $this->data['status'] = 'active';
        }
    }

    public function save(): void
    {
        $employeeId = $this->current?->id;
        $isUpdate = (bool) $employeeId;
        if ($isUpdate && !adminCan('hrm_master_data.update')) {
            abort(403);
        }
        if (!$isUpdate && !adminCan('hrm_master_data.create')) {
            abort(403);
        }

        if (!$isUpdate) {
            $limit = subscriptionFeatureLimit('hrm_employees', 999999);
            $currentEmployees = Employee::query()->count();
            if ($currentEmployees >= $limit) {
                $this->popup('error', 'Employee limit reached. Please upgrade your subscription to add more employees.');
                return;
            }
        }

        $rules = [
            'data.employee_code' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_code')->ignore($employeeId)],
            'data.name' => ['required', 'string', 'max:255'],
            'data.email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employeeId)],
            'data.phone' => ['nullable', 'string', 'max:255'],
            'data.department_id' => ['nullable', 'exists:departments,id'],
            'data.designation_id' => ['nullable', 'exists:designations,id'],
            'data.manager_id' => ['nullable', 'exists:employees,id'],
            'data.hire_date' => ['nullable', 'date'],
            'data.status' => ['required', Rule::in(['active', 'suspended', 'terminated'])],
            'data.password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:6'],
        ];

        $this->validate($rules);

        $payload = $this->data;
        if (($payload['password'] ?? null) === null || trim((string) $payload['password']) === '') {
            unset($payload['password']);
        }

        try {
            DB::beginTransaction();
            if ($isUpdate) {
                $this->employeeService->update($employeeId, $payload);
            } else {
                $this->employeeService->create($payload);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Error while saving employee: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Employee saved successfully');
        $this->dismiss();
        $this->reset('current', 'data');
        $this->data['status'] = 'active';
        $this->dispatch('re-render');
    }

    public function render()
    {
        $departments = $this->departmentService->list([], [], null, 'name');
        $designations = $this->designationService->list(['department'], [], null, 'title');
        $employees = $this->employeeService->list([], [], null, 'name');

        return view('livewire.admin.hrm.master-data.employee-modal', get_defined_vars());
    }
}
