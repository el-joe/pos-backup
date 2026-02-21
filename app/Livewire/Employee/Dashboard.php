<?php

namespace App\Livewire\Employee;

use App\Models\Tenant\AttendanceLog;
use App\Services\Hrm\ExpenseClaimService;
use App\Services\Hrm\LeaveRequestService;
use App\Services\Hrm\PayrollSlipService;
use Livewire\Component;

class Dashboard extends Component
{
    private LeaveRequestService $leaveRequestService;
    private ExpenseClaimService $expenseClaimService;
    private PayrollSlipService $payrollSlipService;

    public function boot(): void
    {
        $this->leaveRequestService = app(LeaveRequestService::class);
        $this->expenseClaimService = app(ExpenseClaimService::class);
        $this->payrollSlipService = app(PayrollSlipService::class);
    }

    public function render()
    {
        $employee = employee();

        $leaveRequestsCount = $this->leaveRequestService->list([], ['employee_id' => $employee->id], null)->count();
        $expenseClaimsCount = $this->expenseClaimService->list([], ['employee_id' => $employee->id], null)->count();
        $payslipsCount = $this->payrollSlipService->list([], ['employee_id' => $employee->id], null)->count();

        $latestAttendanceLog = AttendanceLog::query()
            ->where('employee_id', $employee->id)
            ->orderByDesc('id')
            ->first();

        return employeeLayoutView('employee.dashboard', get_defined_vars(), false)
            ->title('Employee Dashboard');
    }
}
