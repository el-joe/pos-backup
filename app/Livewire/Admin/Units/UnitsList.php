<?php

namespace App\Livewire\Admin\Units;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Unit;
use App\Services\UnitService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class UnitsList extends Component
{
    use LivewireOperations;

    public $current;
    private $unitService;

    public $export = null;
    public $filters = [];
    public $collapseFilters = false;

    function boot() {
        $this->unitService = app(UnitService::class);
    }

    function setCurrent($id) {
        $this->current = $this->unitService->find($id);
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        AuditLog::log(AuditLogActionEnum::DELETE_UNIT_TRY, ['id' => $id]);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Unit', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Unit not found');
            return;
        }

        $id = $this->current->id;

        $this->unitService->delete($id);

        AuditLog::log(AuditLogActionEnum::DELETE_UNIT, ['id' => $id]);

        $this->popup('success', 'Unit deleted successfully');

        $this->dismiss();

        $this->reset('current');
    }

    #[On('re-render')]
    public function render()
    {
        $units = Unit::filter($this->filters);

        if ($this->export == 'excel') {

            $data = $units->get()->map(function ($unit, $loop) {
                return [
                    'loop' => $loop + 1,
                    'name' => $unit->name,
                    'parent' => $unit->parent?->name ?? 'N/A',
                    'count' => $unit->count,
                    'active' => $unit->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'parent', 'count', 'active'];
            $headers = ['#', 'Name', 'Parent', 'Count', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'units');

            AuditLog::log(AuditLogActionEnum::EXPORT_UNITS, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);

            $this->export = null;
        }

        $units = $units->paginate(10);


        $filterUnits = Unit::with('children')
            ->get();

        return layoutView('units.units-list', get_defined_vars())
            ->title(__('general.titles.units'));
    }
}
