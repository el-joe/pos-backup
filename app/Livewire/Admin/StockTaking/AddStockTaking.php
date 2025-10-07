<?php

namespace App\Livewire\Admin\StockTaking;

use App\Services\BranchService;
use App\Services\ProductService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AddStockTaking extends Component
{
    use LivewireOperations,WithPagination;

    private $branchService, $productService;

    public $data = [];
    public $stocks = [];
    public $product_search = '';

    function boot() {
        $this->branchService = app(BranchService::class);
        $this->productService = app(ProductService::class);
    }

    function updatingDataBranchId($value) {
        if($this->data['countedStock'] ?? false){
            $this->data['countedStock'] = [];
        }
    }

    function refactorProductStock($product) {
        foreach ($product->units() as $unit) {
            if(!isset($this->data['countedStock'])){
                $this->data['countedStock'] = [];
            }
            if(!isset($this->data['countedStock'][$product->id])){
                $this->data['countedStock'][$product->id] = [];
            }
            if(!isset($this->data['countedStock'][$product->id][$unit->id])){
                $this->data['countedStock'][$product->id][$unit->id] = $unit->stock($product->id,$this->data['branch_id'])?->qty ?? 0;
            }
            if(!isset($this->stocks)){
                $this->stocks = [];
            }
            if(!isset($this->stocks[$product->id])){
                $this->stocks[$product->id] = [];
            }

            if(isset($this->stocks[$product->id][$unit->id])){
                continue;
            }

            $this->stocks[$product->id][$unit->id] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'current_stock' => $this->data['countedStock'][$product->id][$unit->id],
                'actual_stock' => $this->data['countedStock'][$product->id][$unit->id],
            ];
        }
    }

    public function updatingProductSearch($value)
    {
        $product = $this->productService->search($value);
        if(!$product) {
            $this->alert('warning','Product not found');
            return;
        }
        if(empty($value))return;

        if(isset($this->stocks[$product->id]) && count($this->stocks[$product->id]) == $product->units()->count()){
            $this->alert('warning','Product already added in the list');
            $this->product_search = '';
            $this->dispatch('reset-search-input');
            return;
        }


        $this->refactorProductStock($product);

        $this->product_search = '';
        $this->dispatch('reset-search-input');
    }

    function removeProductStock($productId, $unitId) {
        if(isset($this->stocks[$productId][$unitId])){
            unset($this->stocks[$productId][$unitId]);
            if(isset($this->data['countedStock'][$productId][$unitId])){
                unset($this->data['countedStock'][$productId][$unitId]);
            }
            if(empty($this->stocks[$productId])){
                unset($this->stocks[$productId]);
            }
        }
    }

    public function render()
    {
        $branches = $this->branchService->activeList();
        $stocks = $this->stocks;

        return view('livewire.admin.stock-taking.add-stock-taking',get_defined_vars());
    }
}
