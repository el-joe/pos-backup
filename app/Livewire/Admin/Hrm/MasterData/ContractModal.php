<?php

namespace App\Livewire\Admin\Hrm\MasterData;

use App\Services\Hrm\EmployeeContractService;
use App\Services\Hrm\EmployeeService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ContractModal extends Component
{
    use LivewireOperations;

    public array $data = [
        'employee_id' => null,
        'basic_salary' => null,
        'start_date' => null,
        'end_date' => null,
    ];

    private EmployeeService $employeeService;
    private EmployeeContractService $employeeContractService;

    public function boot(): void
    {
        $this->employeeService = app(EmployeeService::class);
        $this->employeeContractService = app(EmployeeContractService::class);
    }

    #[On('hrm-contract-set-employee')]
    public function setEmployee($employeeId = null): void
    {
        $this->data['employee_id'] = $employeeId;
    }

    public function save(): void
    {
        if (!adminCan('hrm_master_data.create')) {
            abort(403);
        }

        $this->validate([
            'data.employee_id' => 'required|exists:employees,id',
            'data.basic_salary' => 'required|numeric|min:0',
            'data.start_date' => 'required|date',
            'data.end_date' => 'nullable|date|after_or_equal:data.start_date',
        ]);

        try {
            DB::beginTransaction();
            $this->employeeContractService->replaceActiveContract([
                'employee_id' => $this->data['employee_id'],
                'basic_salary' => $this->data['basic_salary'],
                'start_date' => $this->data['start_date'],
                'end_date' => $this->data['end_date'] ?: null,
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Error while saving contract: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Contract saved successfully');
        $this->dismiss();
        $this->reset('data');
        $this->dispatch('re-render');
    }

    public function render()
    {
        $employees = $this->employeeService->list([], [], null, 'name');
        return view('livewire.admin.hrm.master-data.contract-modal', get_defined_vars());
    }
}
