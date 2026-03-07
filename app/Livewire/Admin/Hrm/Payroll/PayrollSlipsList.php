<?php

namespace App\Livewire\Admin\Hrm\Payroll;

use App\Services\Hrm\EmployeeService;
use App\Services\Hrm\PayrollRunService;
use App\Services\Hrm\PayrollSlipService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PayrollSlipsList extends Component
{
    use WithPagination;

    private PayrollSlipService $payrollSlipService;
    private EmployeeService $employeeService;
    private PayrollRunService $payrollRunService;

    public array $filters = [
        'employee_id' => null,
        'payroll_run_id' => null,
    ];

    public bool $collapseFilters = false;

    public function boot(): void
    {
        $this->payrollSlipService = app(PayrollSlipService::class);
        $this->employeeService = app(EmployeeService::class);
        $this->payrollRunService = app(PayrollRunService::class);
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'employee_id' => null,
            'payroll_run_id' => null,
        ];
        $this->resetPage();
    }

    #[On('re-render')]
    public function render()
    {
        $slips = $this->payrollSlipService->list(['employee', 'run'], $this->filters, 10, 'id');
        $employees = $this->employeeService->list([], [], null, 'name');
        $runs = $this->payrollRunService->list([], [], null, 'id');

        return layoutView('hrm.payroll.payroll-slips-list', get_defined_vars())
            ->title(__('general.titles.hrm_payslips'));
    }
}
