<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Stock;
use App\Services\ProductService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PosPage extends Component
{
    use LivewireOperations;

    private $productService;
    public $currentProduct,$selectedUnitId,$selectedQuantity,$maxQuantity;
    public $data = [];

    function boot() {
        $this->productService = app(ProductService::class);
    }

    function updatingSelectedUnitId($value) {
        $this->maxQuantity = Stock::where('product_id', $this->currentProduct->id)
            ->where('unit_id', $value)
            ->when(admin()->branch_id, function($q) {
                $q->where('branch_id', admin()->branch_id);
            })
            ->sum('qty');
    }

    function setCurrentProduct($id) {
        $this->currentProduct = $this->productService->find($id);
    }

    function addToCart($productId = null) {
        if($productId){
            $this->refactorProductData($productId);
        }else{
            $this->refactorProductData($this->currentProduct->id,$this->selectedUnitId,$this->selectedQuantity);
        }

        $this->dismiss();
        $this->reset(['selectedUnitId','selectedQuantity','maxQuantity']);
    }

    function refactorProductData($productId,$unitId = null,$quantity = 1) {
        $product = $this->productService->find($productId);
        $unit = $product->units()->firstWhere('id',$unitId ?? $product->unit_id);
        $stock = Stock::where('product_id', $product->id)
            ->where('unit_id', $unit->id)
            ->when(admin()->branch_id, function($q) {
                $q->where('branch_id', admin()->branch_id);
            })
            ->first();

        $dataProduct = collect($this->data['products'] ?? [])
            ->where('id', $product->id)
            ->where('unit_id', $unit->id)
            ->first();

        if($dataProduct) {
            $quantity = ($quantity ?? 1) + $dataProduct['quantity'];

            // get index of existing product in cart to update
            $index = collect($this->data['products'])->search(function ($item) use ($product, $unit) {
                return $item['id'] === $product->id && $item['unit_id'] === $unit->id;
            });

            $this->data['products'][$index] = [
                ...$this->data['products'][$index],
                'quantity' => $quantity,
                'subtotal' => $quantity * $stock->sell_price,
            ];
        }else{
            $quantity = $quantity ?? 1;
            $this->data['products'][] = [
                'id' => $product->id,
                'name' => $product->name . ' - ' . $unit->name,
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'quantity' => $quantity,
                'sell_price' => $stock->sell_price,
                'subtotal' => $quantity * $stock->sell_price,
            ];
        }
    }

    function calculateTotals() : array {
        $subTotal = collect($this->data['products'] ?? [])->sum('subtotal');
        $tax = 0;
        $discount = 0;
        $total = $subTotal + $tax - $discount;
        return get_defined_vars();
    }

    public function render()
    {
        $products = $this->productService->getAllProductWhereHasStock();
        extract($this->calculateTotals());
        return view('livewire.admin.pos-page',get_defined_vars());
    }
}
