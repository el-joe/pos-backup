<?php

namespace App\Livewire\Admin\HRM\Payroll;

use App\Enums\PayslipStatusEnum;
use App\Models\Tenant\Payslip;
use App\Models\Tenant\Employee;
use App\Models\Tenant\EmployeeSalary;
use App\Models\Tenant\Attendance;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class PayslipsList extends Component
{
    use WithPagination;

    public $collapseFilters = false;

    public $search = '';
    public $employee_filter = '';
    public $year_filter;
    public $month_filter;
    public $status_filter = '';

    public function mount()
    {
        $this->year_filter = Carbon::now()->year;
        $this->month_filter = Carbon::now()->month;
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'employee_filter', 'status_filter');
        $this->year_filter = Carbon::now()->year;
        $this->month_filter = Carbon::now()->month;
        $this->resetPage();
    }

    public function generatePayslips()
    {
        // Get all active employees
        $employees = Employee::where('status', 'active')->get();

        foreach ($employees as $employee) {
            // Check if payslip already exists
            $exists = Payslip::where('employee_id', $employee->id)
                ->where('year', $this->year_filter)
                ->where('month', $this->month_filter)
                ->exists();

            if ($exists) {
                continue;
            }

            // Get current salary
            $salary = $employee->currentSalary()->first();
            if (!$salary) {
                continue;
            }

            // Calculate attendance
            $startDate = Carbon::create($this->year_filter, $this->month_filter, 1)->startOfMonth();
            $endDate = Carbon::create($this->year_filter, $this->month_filter, 1)->endOfMonth();

            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $workingDays = $startDate->daysInMonth;
            $presentDays = $attendance->where('status', 'present')->count();
            $absentDays = $attendance->where('status', 'absent')->count();
            $leaveDays = $attendance->where('status', 'on_leave')->count();

            $allowances = $salary->allowances ?? [];
            $deductions = $salary->deductions ?? [];

            $totalAllowances = collect($allowances)->sum('amount');
            $totalDeductions = collect($deductions)->sum('amount');

            // Create payslip
            Payslip::create([
                'employee_id' => $employee->id,
                'employee_salary_id' => $salary->id,
                'year' => $this->year_filter,
                'month' => $this->month_filter,
                'basic_salary' => $salary->basic_salary,
                'allowances' => $allowances,
                'total_allowances' => $totalAllowances,
                'deductions' => $deductions,
                'total_deductions' => $totalDeductions,
                'working_days' => $workingDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'leave_days' => $leaveDays,
                'gross_salary' => $salary->gross_salary,
                'net_salary' => $salary->net_salary,
                'status' => PayslipStatusEnum::GENERATED,
                'generated_by' => auth()->id(),
            ]);
        }

        session()->flash('success', __('hrm.payslips_generated_successfully'));
    }

    public function render()
    {
        $payslips = Payslip::query()
            ->with(['employee', 'employeeSalary'])
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->employee_filter, fn($q) => $q->where('employee_id', $this->employee_filter))
            ->when($this->year_filter, fn($q) => $q->where('year', $this->year_filter))
            ->when($this->month_filter, fn($q) => $q->where('month', $this->month_filter))
            ->when($this->status_filter, fn($q) => $q->where('status', $this->status_filter))
            ->latest()
            ->paginate(25);

        $employees = Employee::where('status', 'active')->get();
        $statuses = PayslipStatusEnum::cases();

        return layoutView('hrm.payroll.payslips-list', get_defined_vars())
            ->title(__('hrm.payslips'));
    }
}
