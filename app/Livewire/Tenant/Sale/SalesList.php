<?php

namespace App\Livewire\Tenant\Sale;

use App\Models\Tenant\Sale;
use App\Traits\LivewireOperations;
use Livewire\Component;

class SalesList extends Component
{
    use LivewireOperations;

    public $current;

    public $model = Sale::class;

    function setCurrent($id) {
        $this->current = $this->model::find($id);
    }
    public function render()
    {
        $sales = $this->model::with('customer','branch','saleVariables')->orderByDesc('id')->paginate(20);
        return view('livewire.tenant.sale.sales-list',get_defined_vars());
    }
}
