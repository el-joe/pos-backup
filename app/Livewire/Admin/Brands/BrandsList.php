<?php

namespace App\Livewire\Admin\Brands;

use App\Services\BrandService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class BrandsList extends Component
{
    use LivewireOperations, WithPagination;
    private $brandService;
    public $current;
    public $data = [];

    public $rules = [
        'name' => 'required|string|max:255',
        'active' => 'boolean',
    ];

    function boot() {
        $this->brandService = app(BrandService::class);
    }

    function setCurrent($id) {
        $this->current = $this->brandService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this brand', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Brand not found');
            return;
        }

        $this->brandService->delete($this->current->id);

        $this->popup('success', 'Brand deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        $this->brandService->save($this->current?->id, $this->data);

        $this->popup('success', 'Brand saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $brands = $this->brandService->list([], [], 10, 'id');

        return layoutView('brands.brands-list', get_defined_vars())
            ->title(__('general.titles.brands'));
    }
}
