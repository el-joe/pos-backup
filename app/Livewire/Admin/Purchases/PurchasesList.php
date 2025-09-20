<?php

namespace App\Livewire\Admin\Purchases;

use App\Services\PurchaseService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PurchasesList extends Component
{

    use LivewireOperations,WithPagination;
    private $purchaseService;
    public $current;

    function boot() {
        $this->purchaseService = app(PurchaseService::class);
    }

    function setCurrent($id) {
        $this->current = $this->purchaseService->find($id);
    }
    public function render()
    {
        $purchases = $this->purchaseService->list([],[],10,'id');
        return view('livewire.admin.purchases.purchases-list',get_defined_vars());
    }
}
