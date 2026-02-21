<?php

namespace App\Livewire\Admin\Hrm\Payroll;

use App\Services\Hrm\PayrollSlipService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PayrollSlipsList extends Component
{
    use WithPagination;

    private PayrollSlipService $payrollSlipService;

    public array $filters = [
        'employee_id' => null,
        'payroll_run_id' => null,
    ];

    public bool $collapseFilters = false;

    public function boot(): void
    {
        $this->payrollSlipService = app(PayrollSlipService::class);
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

        return layoutView('hrm.payroll.payroll-slips-list', get_defined_vars())
            ->title('HRM Payslips');
    }
}
