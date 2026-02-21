<?php

namespace App\Livewire\Admin\Hrm\Designations;

use App\Services\Hrm\DesignationService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DesignationsList extends Component
{
    use LivewireOperations, WithPagination;

    private DesignationService $designationService;

    public array $filters = [];
    public bool $collapseFilters = false;

    public $current;

    public function boot(): void
    {
        $this->designationService = app(DesignationService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->designationService->find($id);
    }

    public function deleteAlert($id): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this designation', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!adminCan('hrm_master_data.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Designation not found');
            return;
        }

        $this->designationService->delete($this->current->id);
        $this->popup('success', 'Designation deleted successfully');
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->resetPage();
    }

    #[On('re-render')]
    public function render()
    {
        $designations = $this->designationService->list(['department'], $this->filters, 10, 'id');

        return layoutView('hrm.designations.designations-list', get_defined_vars())
            ->title('HRM Designations');
    }
}
