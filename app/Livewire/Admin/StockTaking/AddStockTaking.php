<?php

namespace App\Livewire\Admin\StockTaking;

use App\Services\BranchService;
use App\Services\ProductService;
use App\Services\StockTakingService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class AddStockTaking extends Component
{
    use LivewireOperations,WithPagination;

    private $branchService, $productService , $stockTakingService;

    public $data = [];
    public $stocks = [];
    public $product_search = '';

    public $rules = [
        'branch_id' => 'required|integer|exists:branches,id',
        'date' => 'required|date',
        'note' => 'nullable|string|max:255',
        'countedStock' => 'array',
        'countedStock.*' => 'array',
        'countedStock.*.*' => 'integer|min:0',
    ];

    function boot() {
        $this->branchService = app(BranchService::class);
        $this->productService = app(ProductService::class);
        $this->stockTakingService = app(StockTakingService::class);
    }

    function updatingDataBranchId($value) {
        if($this->data['countedStock'] ?? false){
            $this->data['countedStock'] = [];
        }
    }

    function refactorProductStock($product) {
        foreach ($product->units() as $unit) {
            $stock = $unit->stock($product->id,$this->data['branch_id']);
            if(!$stock){
                $this->alert('warning','No stock found for '.$product->name.' in '.$unit->name);
                continue;
            }

            if(!isset($this->data['countedStock'])){
                $this->data['countedStock'] = [];
            }
            if(!isset($this->data['countedStock'][$product->id])){
                $this->data['countedStock'][$product->id] = [];
            }
            if(!isset($this->data['countedStock'][$product->id][$unit->id])){
                $this->data['countedStock'][$product->id][$unit->id] = $stock?->qty ?? 0;
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
                'stock_id' => $stock?->id ?? null,
                'unit_cost' => $stock?->unit_cost ?? 0,
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

    function save() {
        if (!$this->validator()) return;

        // Save logic to be implemented
        $data = $this->data;
        $data['stocks'] = collect($this->stocks ?? [])->flatten(1)->values()->toArray();

        $st = $this->stockTakingService->save(null,$data);

        $this->popup('success', 'Stock Take saved successfully');

        $this->reset('data', 'stocks');

        return $this->redirectWithTimeout(route('admin.stocks.adjustments.details', $st->id), 2000);
    }
    public function render()
    {
        $branches = $this->branchService->activeList();
        $stocks = $this->stocks;

        return layoutView('stock-taking.add-stock-taking', get_defined_vars());
    }
}
