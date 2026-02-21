<?php

namespace App\Livewire\Employee\Payslips;

use App\Services\Hrm\PayrollSlipService;
use Livewire\Component;
use Livewire\WithPagination;

class PayslipsList extends Component
{
    use WithPagination;

    private PayrollSlipService $payrollSlipService;

    public function boot(): void
    {
        $this->payrollSlipService = app(PayrollSlipService::class);
    }

    public function render()
    {
        $employee = employee();
        $payslips = $this->payrollSlipService->list(['run'], ['employee_id' => $employee->id], 10, 'id');

        return employeeLayoutView('employee.payslips.payslips-list', get_defined_vars())
            ->title('My Payslips');
    }
}
