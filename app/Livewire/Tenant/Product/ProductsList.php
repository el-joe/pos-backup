<?php

namespace App\Livewire\Tenant\Product;

use App\Models\Tenant\Product;
use App\Traits\LivewireOperations;
use Livewire\Component;

class ProductsList extends Component
{

    use LivewireOperations;

    public $current;

    public $model = Product::class;

    function setCurrent($id) {
        $this->current = $this->model::find($id);
    }

    function delete() {
        $this->current->delete();
        $this->swal('Success!','Deleted Successfully!','success');
        $this->dismiss();
    }
    public function render()
    {
        $products = $this->model::query();

        $products = $products->paginate(20);

        return view('livewire.tenant.product.products-list',get_defined_vars());
    }
}
