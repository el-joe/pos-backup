<?php

namespace App\Livewire\Admin\Hrm\Contracts;

use App\Services\Hrm\EmployeeContractService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ContractsList extends Component
{
    use LivewireOperations, WithPagination;

    private EmployeeContractService $employeeContractService;

    public array $filters = [];
    public bool $collapseFilters = false;

    public function boot(): void
    {
        $this->employeeContractService = app(EmployeeContractService::class);
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->resetPage();
    }

    #[On('re-render')]
    public function render()
    {
        $contracts = $this->employeeContractService->list(['employee'], $this->filters, 10, 'id');

        return layoutView('hrm.contracts.contracts-list', get_defined_vars())
            ->title('HRM Contracts');
    }
}
