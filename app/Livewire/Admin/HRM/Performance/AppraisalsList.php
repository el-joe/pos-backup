<?php

namespace App\Livewire\Admin\HRM\Performance;

use App\Models\Tenant\PerformanceAppraisal;
use App\Models\Tenant\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class AppraisalsList extends Component
{
    use WithPagination;

    public $collapseFilters = false;

    public $search = '';
    public $employee_filter = '';
    public $period_filter = '';

    public function resetFilters(): void
    {
        $this->reset('search', 'employee_filter', 'period_filter');
        $this->resetPage();
    }

    public function render()
    {
        $appraisals = PerformanceAppraisal::query()
            ->with(['employee', 'appraiser', 'reviewer'])
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
                })->orWhere('appraisal_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->employee_filter, fn($q) => $q->where('employee_id', $this->employee_filter))
            ->when($this->period_filter, fn($q) => $q->where('appraisal_period', $this->period_filter))
            ->latest()
            ->paginate(25);

        $employees = Employee::where('status', 'active')->get();

        return layoutView('hrm.performance.appraisals-list', get_defined_vars())
            ->title(__('hrm.performance_appraisals'));
    }
}
