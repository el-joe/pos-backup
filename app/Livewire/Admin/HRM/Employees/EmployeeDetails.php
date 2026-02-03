<?php

namespace App\Livewire\Admin\HRM\Employees;

use App\Models\Tenant\Employee;
use Livewire\Component;

class EmployeeDetails extends Component
{
    public $employee;
    public $activeTab = 'profile';

    public function mount($id)
    {
        $this->employee = Employee::with([
            'department',
            'designation',
            'branch',
            'manager',
            'documents',
            'attendances' => fn($q) => $q->latest()->take(10),
            'leaveRequests' => fn($q) => $q->latest()->take(10),
            'currentSalary',
            'payslips' => fn($q) => $q->latest()->take(10),
        ])->findOrFail($id);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.hrm.employees.employee-details');
    }
}
