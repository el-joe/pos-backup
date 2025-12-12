<?php

namespace App\Livewire\Admin\Brands;

use App\Services\BrandService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BrandsList extends Component
{
    use LivewireOperations, WithPagination;
    private $brandService;
    public $current;

    public $export;
    public $filters = [];

    public $collapseFilters = false;

    function boot() {
        $this->brandService = app(BrandService::class);
    }

    function setCurrent($id) {
        $this->current = $this->brandService->find($id);
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

        $this->reset('current');
    }

    #[On('re-render')]
    public function render()
    {
        if ($this->export == 'excel') {
            $qData = $this->brandService->list(
                orderByDesc : 'brands.id',
                filter : $this->filters
            );

            $data = $qData->map(function ($brand, $loop) {
                return [
                    'loop' => $loop + 1,
                    'name' => $brand->name,
                    'active' => $brand->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'active'];
            $headers = ['#', 'Name', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'brands');

            $this->redirectToDownload($fullPath);
        }

        $brands = $this->brandService->list([], $this->filters, 10, 'id');

        return layoutView('brands.brands-list', get_defined_vars())
            ->title(__('general.titles.brands'));
    }
}
