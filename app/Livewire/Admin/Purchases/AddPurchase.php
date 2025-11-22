<?php

namespace App\Livewire\Admin\Purchases;

use App\Helpers\PurchaseHelper;
use App\Models\Tenant\Product;
use App\Services\AccountService;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\ProductService;
use App\Services\PurchaseService;
use App\Services\TaxService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;


class AddPurchase extends Component
{
    use LivewireOperations;

    private $userService , $branchService , $productService , $taxService , $accountService , $purchaseService , $cashRegisterService;

    public $data = [];

    public $orderProducts = [];

    public $rules = [
        // Details Section
        'branch_id' => 'required|integer',
        'supplier_id' => 'required|integer',
        'order_date' => 'required|date',
        'ref_no' => 'required|string|max:255',
        // Products Section
        'orderProducts' => 'required|array|min:1',
        'orderProducts.*.id' => 'required|integer',
        'orderProducts.*.unit_id' => 'required|integer',
        'orderProducts.*.qty' => 'required|numeric|min:1',
        'orderProducts.*.purchase_price' => 'required|numeric|min:0',
        'orderProducts.*.discount_percentage' => 'nullable|numeric|min:0',
        'orderProducts.*.tax_percentage' => 'nullable|numeric|min:0',
        'orderProducts.*.x_margin' => 'nullable|numeric|min:0',
        // Order Adjustments Section
        'tax_id' => 'nullable|numeric',
        'discount_type' => 'nullable|in:fixed,percentage',
        'discount_value' => 'nullable|numeric',
        // Expenses Section
        'expenses' => 'nullable|array',
        'expenses.*.description' => 'nullable|string|max:255',
        'expenses.*.amount' => 'required|numeric',
        'expenses.*.expense_date' => 'nullable|date',
        // Payment Section
        'payment_status' => 'required|in:pending,partial_paid,full_paid',
        'payment_account' => 'required_if:payment_status,partial_paid,full_paid|integer',
        'payment_amount' => 'required_if:payment_status,partial_paid|numeric',
        'payment_note' => 'nullable|string',
    ];

    public $product_search = '';

    function boot() {
        $this->userService = app(UserService::class);
        $this->purchaseService = app(PurchaseService::class);
        $this->branchService = app(BranchService::class);
        $this->productService = app(ProductService::class);
        $this->taxService = app(TaxService::class);
        $this->accountService = app(AccountService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    function mount(){
        if(admin()->branch_id){
            $this->data['branch_id'] = admin()->branch_id;
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

        $productDetails = $this->refactorProduct($product);
        // if(isset($this->orderProducts[$product->id])) {
        //     $this->alert('info','Product already added in the list');
        //     return;
        // }
        $this->orderProducts[] = $productDetails;

        $this->reset('product_search');
        $this->dispatch('reset-search-input');
    }

    function updatingDataTaxId($value) {
        $this->data['tax_rate'] = $this->taxService->find($value)?->rate ?? 0;
    }

    function updatingOrderProducts($value,$key){
        $parts = explode('.',$key);
        if(count($parts) != 2) return;
        $index = $parts[0];
        $productId = $this->orderProducts[$index]['id'] ?? null;
        if(!$productId) return;
        $product = $this->productService->find($productId);
        $key = $parts[1];
        $this->orderProducts[$index] = $this->refactorProduct($product,$index,$key,$value);
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

    function delete($index) {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
        $this->alert('success','Product removed from the list');
    }

    public function addExpense()
    {
        $this->data['expenses'][] = [
            'description' => '',
            'amount' => 0,
            'expense_date' => null,
        ];
    }

    function removeExpense($index) {
        if(isset($this->data['expenses'][$index])){
            unset($this->data['expenses'][$index]);
            $this->data['expenses'] = array_values($this->data['expenses']);
        }
        $this->alert('success','Expense removed from the list');
    }

    function calcOrderProductsTotal() : float {
        $products = collect($this->orderProducts);
        return $products->sum(fn($q)=>$q['sub_total'] * $q['qty']);
    }

    function calcExpensesTotal() : float {
        return array_sum(array_column($this->data['expenses'] ?? [],'amount'));
    }

    function purchaseCalculations() {
        $orderSubTotal = PurchaseHelper::calcSubtotal($this->calcOrderProductsTotal(),$this->calcExpensesTotal());
        $orderDiscountAmount = PurchaseHelper::calcDiscount($orderSubTotal,$this->data['discount_type'] ?? null,$this->data['discount_value'] ?? 0);
        $orderTotalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($orderSubTotal,$orderDiscountAmount);
        $orderTaxAmount = PurchaseHelper::calcTax($orderTotalAfterDiscount,$this->data['tax_rate'] ?? 0);
        $orderGrandTotal = PurchaseHelper::calcGrandTotal($orderTotalAfterDiscount,$orderTaxAmount);

        return [
            'orderSubTotal' => $orderSubTotal,
            'orderDiscountAmount' => $orderDiscountAmount,
            'orderTotalAfterDiscount' => $orderTotalAfterDiscount,
            'orderTaxAmount' => $orderTaxAmount,
            'orderGrandTotal' => $orderGrandTotal,
        ];
    }

    function savePurchase() {
        // validation
        if(!$this->validator([
            ...$this->data,
            'orderProducts' => $this->orderProducts
        ]))return;
        // save purchase
        $calcDetails = $this->purchaseCalculations();

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $this->cashRegisterService->increment($cashRegister->id, 'total_purchases', $calcDetails['orderGrandTotal']);
        }

        // save purchase
        $this->purchaseService->save(null,[
            ...$this->data,
            'tax_percentage'=> $this->data['tax_rate'] ?? 0,
            'orderProducts' => $this->orderProducts,
            'total' => $this->calcOrderProductsTotal(),
            'expenses_total' => $this->calcExpensesTotal(),
            'sub_total' => $calcDetails['orderSubTotal'],
            'discount_amount' => $calcDetails['orderDiscountAmount'] ?? 0,
            'total_after_discount' => $calcDetails['orderTotalAfterDiscount'] ?? 0,
            'tax_amount' => $calcDetails['orderTaxAmount'] ?? 0,
            'grand_total' => $calcDetails['orderGrandTotal'] ?? 0,
        ]);
        // alert success
        $this->alert('success','Purchase created successfully');
        // redirect to purchases list
        return $this->redirectWithTimeout(route('admin.purchases.list'),1000);
    }

    public function render()
    {
        $suppliers = $this->userService->suppliersList([],[],null,'name');
        $branches = $this->branchService->activeList([],[],null,'name');
        $taxes = $this->taxService->activeList([],[],null,'name');
        $paymentAccounts = [];

        if($this->data['supplier_id'] ?? null)
            $paymentAccounts = $this->accountService->getSupplierAccounts($this->data['supplier_id']);

        $totalQuantity = array_sum(array_column($this->orderProducts,'qty'));
        list($orderSubTotal,$orderDiscountAmount,$orderTotalAfterDiscount,$orderTaxAmount,$orderGrandTotal) = array_values($this->purchaseCalculations());

        return layoutView('purchases.add-purchase', get_defined_vars())
            ->title(__( 'general.titles.add_purchase' ));
    }
}
