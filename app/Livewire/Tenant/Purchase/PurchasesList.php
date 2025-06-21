<?php

namespace App\Livewire\Tenant\Purchase;

use App\Models\Tenant\Purchase;
use App\Traits\LivewireOperations;
use Livewire\Component;

class PurchasesList extends Component
{
    use LivewireOperations;

    public $current;

    public $model = Purchase::class;

    function setCurrent($id) {
        $this->current = $this->model::find($id);
    }

    public function render()
    {
        $purchases = $this->model::with('supplier','branch','purchaseVariables')->orderByDesc('id')->paginate(20);
        return view('livewire.tenant.purchase.purchases-list',get_defined_vars());
    }
}
