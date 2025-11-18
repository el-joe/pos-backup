<?php

namespace App\Livewire\Admin\Taxes;

use App\Services\TaxService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class TaxesList extends Component
{
    use LivewireOperations, WithPagination;
    private $taxService;
    public $current;
    public $data = [];

    public $filters = [];
    public $export;
    public $collapseFilters = false;

    public $rules = [
        'name' => 'required|string|max:255',
        'rate' => 'required|numeric',
        'active' => 'boolean',
    ];

    function boot() {
        $this->taxService = app(TaxService::class);
    }

    function setCurrent($id) {
        $this->current = $this->taxService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this tax', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Tax not found');
            return;
        }

        $this->taxService->delete($this->current->id);

        $this->popup('success', 'Tax deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        $this->taxService->save($this->current?->id, $this->data);

        $this->popup('success', 'Tax saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        if ($this->export == 'excel') {
            $taxes = $this->taxService->list(filter: $this->filters, orderByDesc: 'id');

            $data = $taxes->map(function ($tax, $loop) {
                #	Name	Percentage	Status
                return [
                    'loop' => $loop + 1,
                    'name' => $tax->name,
                    'rate' => $tax->rate,
                    'active' => $tax->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'rate', 'active'];
            $headers = ['#', 'Name', 'Percentage', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'taxes');

            $this->redirectToDownload($fullPath);
        }

        $taxes = $this->taxService->list(perPage: 10, orderByDesc: 'id', filter: $this->filters);

        return layoutView('taxes.taxes-list', get_defined_vars())
            ->title(__('general.titles.taxes'));
    }
}
