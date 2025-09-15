<?php

namespace App\Livewire\Admin\Purchases;

use App\Enums\PurchaseStatusEnum;
use App\Services\BranchService;
use App\Services\ProductService;
use App\Services\TaxService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AddPurchase extends Component
{
    use LivewireOperations;

    private $userService , $branchService , $productService , $taxService;

    public $data = [];

    public $orderProducts = [];

    public $rules = [];

    public $product_search = '';

    function boot() {
        $this->userService = app(UserService::class);
        $this->branchService = app(BranchService::class);
        $this->productService = app(ProductService::class);
        $this->taxService = app(TaxService::class);
    }

    public function updatingProductSearch($value)
    {
        $product = $this->productService->search($value);
        if(!$product) {
            $this->alert('warning','Product not found');
            return;
        }

        $productDetails = $this->refactorProduct($product);
        if(isset($this->orderProducts[$product->id])) {
            $this->alert('info','Product already added in the list');
            return;
        }
        $this->orderProducts[$product->id] = $productDetails;

        $this->dispatch('reset-search-input');
    }

    function updatingOrderProducts($value,$key){
        $parts = explode('.',$key);
        if(count($parts) != 2) return;
        $productId = $parts[0];
        $product = $this->productService->find($productId);
        $key = $parts[1];
        $this->orderProducts[$productId] = $this->refactorProduct($product,$productId,$key,$value);
    }

    function refactorProduct($product,$index = null,$key = null,$value = null) : array {
        $orderProductData = $this->orderProducts[$index] ?? [];
        if($key){
            $orderProductData[$key] = $value;
        }
        $newArr = [
            'id' => $product->id,
            'name' => $product->name,
            'unit_id' => $orderProductData['unit_id'] ?? '',
            'units' => $product->units() ?? [],
            'qty' => $orderProductData['qty'] ?? 1,
            'purchase_price' => $orderProductData['purchase_price'] ?? 0,
            'discount_percentage' => $orderProductData['discount_percentage'] ?? 0,
        ];

        $newArr['unit_cost_after_discount'] = $newArr['purchase_price'] - ($newArr['purchase_price'] * $newArr['discount_percentage'] / 100);
        $newArr['tax_percentage'] = $orderProductData['tax_percentage'] ?? 0;
        $newArr['sub_total'] = $newArr['unit_cost_after_discount'] + ($newArr['unit_cost_after_discount'] * $newArr['tax_percentage'] / 100);
        $newArr['x_margin'] = $orderProductData['x_margin'] ?? 0;
        $newArr['sell_price'] = $newArr['sub_total'] + ($newArr['sub_total'] * $newArr['x_margin'] / 100);
        $newArr['total'] = $newArr['sell_price'] * $newArr['qty'];
        return $newArr;
    }

    public function render()
    {
        $suppliers = $this->userService->suppliersList([],[],null,'name');
        $branches = $this->branchService->activeList([],[],null,'name');
        $purchaseStatuses = PurchaseStatusEnum::cases();
        $netTotalAmount = array_sum(array_column($this->orderProducts ?? [],'sub_total'));
        $taxes = $this->taxService->activeList([],[],null,'name');
        return view('livewire.admin.purchases.add-purchase',get_defined_vars());
    }
}
