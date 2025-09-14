<?php

namespace App\Livewire\Admin\Products;

use App\Services\ProductService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ProductsList extends Component
{

    use LivewireOperations,WithPagination;
    private $productService;
    public $current;

    function boot() {
        $this->productService = app(ProductService::class);
    }

    function setCurrent($id) {
        $this->current = $this->productService->find($id);
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete','warning','Are you sure?','You want to delete this product','Yes, delete it!');
    }

    function delete() {
        if(!$this->current) {
            $this->popup('error','Product not found');
            return;
        }

        $this->productService->delete($this->current->id);

        $this->popup('success','Product deleted successfully');

        $this->dismiss();

        $this->reset('current');
    }

    public function render()
    {
        $products = $this->productService->list(perPage : 10 , orderByDesc : 'id');
        return view('livewire.admin.products.products-list',get_defined_vars());
    }
}
