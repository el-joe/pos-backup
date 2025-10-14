<?php

namespace App\Livewire\Admin;

use App\Helpers\SaleHelper;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Discount;
use App\Models\Tenant\Sale;
use App\Models\Tenant\Setting;
use App\Models\Tenant\Stock;
use App\Models\Tenant\User;
use App\Services\BranchService;
use App\Services\ProductService;
use App\Services\SellService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PosPage extends Component
{
    use LivewireOperations;

    private $productService, $userService, $sellService, $branchService;
    public $currentProduct,$selectedUnitId,$selectedQuantity,$maxQuantity,$discountCode,$selectedCustomerId;
    public $data = [];
    public $payments = [];
    public $branch;

    function boot() {
        $this->productService = app(ProductService::class);
        $this->userService = app(UserService::class);
        $this->sellService = app(SellService::class);
        $this->branchService = app(BranchService::class);
    }

    function updatingSelectedUnitId($value) {
        $this->maxQuantity = Stock::where('product_id', $this->currentProduct->id)
            ->where('unit_id', $value)
            ->when($this->data['branch_id'], function($q) {
                $q->where('branch_id', $this->data['branch_id']);
            })
            ->sum('qty');
    }

    function updatingDataBranchId($value) {
        $this->branch = $this->branchService->find($value);
    }

    function setCurrentProduct($id) {
        $this->currentProduct = $this->productService->find($id);
    }

    function addToCart($productId = null) {
        if($productId){
            $responseStatus = $this->refactorProductData($productId);
        }else{
            $responseStatus = $this->refactorProductData($this->currentProduct->id,$this->selectedUnitId,$this->selectedQuantity);
        }

        if(!$responseStatus) {
            return;
        }

        $this->dismiss();
        $this->reset(['selectedUnitId','selectedQuantity','maxQuantity']);
    }

    function validateDiscountCode() {
        if(!$this->discountCode) {
            $this->alert('error', 'Please enter a discount code');
            return;
        }

        $discount = Discount::where('code', $this->discountCode)
            ->valid()
            ->first();

        if(!$discount) {
            $this->alert('error', 'Invalid or expired discount code');
            $this->data['discount'] = null;
            return;
        }

        if($this->selectedCustomerId){
            $history = $discount->history()->where('target_type',User::class)
            ->where('target_id',$this->selectedCustomerId)
            ->count();

            if($discount->usage_limit && $history >= $discount->usage_limit) {
                $this->alert('error', 'Coupon usage limit has been reached');
                $this->data['discount'] = null;
                return;
            }
        }else{
            $this->alert('error', 'Please select a customer to apply this coupon');
            $this->data['discount'] = null;
            return;
        }

        $this->data['discount'] = [
            'id' => $discount->id,
            'name' => $discount->name,
            'type' => $discount->type,
            'code' => $discount->code,
            'value' => $discount->value,
            'max_discount_amount' => $discount->max_discount_amount ?? 99999999,
            'sales_threshold' => $discount->sales_threshold ?? 0,
            'max' => $discount->type == 'fixed' ? ($discount->sales_threshold ?? 0) : ($discount->max_discount_amount ?? 0),
        ];

        $this->alert('success', 'Discount code applied');
        $this->reset('discountCode');
    }

    function removeCoupon() {
        $this->data['discount'] = null;
        $this->alert('success', 'Discount removed');
    }

    function refactorProductData($productId,$unitId = null,$quantity = 1) {
        $product = $this->productService->find($productId);
        $unit = $product->units()->firstWhere('id',$unitId ?? $product->unit_id);
        $stock = Stock::where('product_id', $product->id)
            ->where('unit_id', $unit->id)
            ->when($this->data['branch_id'] ?? false, function($q) {
                $q->where('branch_id', $this->data['branch_id']);
            })
            ->first();

        $dataProduct = collect($this->data['products'] ?? [])
            ->where('id', $product->id)
            ->where('unit_id', $unit->id)
            ->first();

        $quantity = ($quantity ?? 1) + ($dataProduct['quantity'] ?? 0);

        if($stock->qty < $quantity) {
            $this->alert('error', 'Maximum available quantity is ' . $stock->qty);
            return false;
        }

        if($dataProduct) {

            // get index of existing product in cart to update
            $index = collect($this->data['products'])->search(function ($item) use ($product, $unit) {
                return $item['id'] === $product->id && $item['unit_id'] === $unit->id;
            });

            $this->data['products'][$index] = [
                ...$this->data['products'][$index],
                'quantity' => $quantity,
                'subtotal' => $quantity * $stock->sell_price,
                'stock_qty' => $stock->qty,
                'stock_id' => $stock->id,
                'sell_price' => $stock->sell_price,
                'unit_cost' => $stock->unit_cost,
                'taxable' => $product->taxable,
            ];
        }else{
            // $quantity = $quantity ?? 1;
            $this->data['products'][] = [
                'id' => $product->id,
                'name' => $product->name . ' - ' . $unit->name,
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'quantity' => $quantity,
                'subtotal' => $quantity * $stock->sell_price,
                'stock_qty' => $stock->qty,
                'stock_id' => $stock->id,
                'sell_price' => $stock->sell_price,
                'unit_cost' => $stock->unit_cost,
                'taxable' => $product->taxable,
            ];
        }

        return true;
    }

    function addPayment() {
        $this->payments[] = [
            'account_id' => null,
            'amount' => 0,
        ];
    }

    function removePayment($index) {
        unset($this->payments[$index]);
        $this->payments = array_values($this->payments);
    }

    function calculateTotals() : array {
        $subTotal = SaleHelper::subTotal($this->data['products'] ?? []);
        $discount = SaleHelper::discountAmount($this->data['products'] ?? [], $this->data['discount']['type'] ?? null, $this->data['discount']['value'] ?? 0,$this->data['discount']['max'] ?? 0);
        $totalAfterDiscount = $subTotal - $discount;
        $taxPercentage = $this->branch?->tax?->rate ?? 0;
        $tax = SaleHelper::taxAmount($this->data['products'] ?? [], $this->data['discount']['type'] ?? null, $this->data['discount']['value'] ?? 0, $taxPercentage,$this->data['discount']['max'] ?? 0);
        $total = $subTotal + $tax - $discount;
        return get_defined_vars();
    }

    function confirmPayment() {
        extract($this->calculateTotals());
        $products = $this->data['products'] ?? [];
        $customerId = $this->selectedCustomerId ?? null;
        $payments = $this->payments ?? [];

        // validate payments
        $validation = $this->validation();
        if(!$validation)return;
        // store order
        $dataToSave = [
            "customer_id" => $customerId,
            "branch_id" => $this->branch?->id,
            "invoice_number" => $this->data['invoice_number'] ?? null,
            "order_date" => $this->data['order_date'] ?? now(),
            "tax_id" => $this->branch?->tax_id ?? null,
            "tax_percentage" => $taxPercentage ?? 0,
            "discount_id" => $this->data['discount']['id'] ?? null,
            "discount_type" => $this->data['discount']['type'] ?? null,
            "discount_value" => $this->data['discount']['value'] ?? 0,
            "payment_note" => $this->data['payment_note'] ?? null,
            "payment_amount" => $total ?? 0,
            'payments' => $payments ?? [],
            'paid_amount' => array_sum(array_column($payments ?? [], 'amount')),
            'tax_amount'=> $tax ?? 0,
            'discount_amount'=> $discount ?? 0,
            'sell_price' => $subTotal ?? 0
        ];

        foreach ($products as $product) {
            $dataToSave['products'][] = [
                'id' => $product['id'],
                'unit_id' => $product['unit_id'],
                'quantity' => $product['quantity'],
                'unit_cost' => $product['unit_cost'],
                'sell_price' => $product['sell_price'],
                'subtotal' => $product['subtotal'],
                'taxable' => $product['taxable'] ?? false,
            ];
        }
        $this->sellService->save(null,$dataToSave);
        $this->dismiss();
        $this->popup('success', 'Order placed successfully');
        $this->reset(['data','payments','selectedCustomerId']);

        $this->redirectWithTimeout(route('admin.sales.index'), 1000);
    }

    function validation() {
        if(!($this->data['branch_id']??false)){
            $this->alert('error', 'Please select a branch');
            return false;
        }
        if(!($this->data['invoice_number']??false)){
            // auto generate invoice number
            $this->data['invoice_number'] = Sale::generateInvoiceNumber();
        }
        if(empty($this->data['products'] ?? [])) {
            $this->alert('error', 'Please add products to the cart');
            return false;
        }

        foreach ($this->data['products'] as $key => $value) {
            $stock = Stock::find($value['stock_id'] ?? 0);
            if($value['quantity'] > $stock->qty) {
                $this->alert('error', 'Product ' . $value['name'] . ' is out of stock');
                return false;
            }
        }

        extract($this->calculateTotals());

        if(!$this->selectedCustomerId) {
            $this->alert('error', 'Please select a customer');
            return false;
        }

        foreach ($this->payments as $payment) {
            if(!$payment['account_id']) {
                $this->alert('error', 'Please select payment method for all payments');
                return false;
            }
            if($payment['amount'] <= 0) {
                $this->alert('error', 'Please enter valid amount for all payments');
                return false;
            }
        }

        return true;
    }

    public function render()
    {
        $products = $this->productService->getAllProductWhereHasStock([],[
            'branch_id' => $this->data['branch_id'] ?? null,
            'active' => true,
        ]);
        $customers = $this->userService->customersList();
        $selectedCustomer = $customers->firstWhere('id',$this->selectedCustomerId);
        $branches = $this->branchService->activeList();
        extract($this->calculateTotals());
        return view('livewire.admin.pos-page',get_defined_vars());
    }
}
