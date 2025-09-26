<?php

namespace App\Livewire\Admin\Purchases;

use App\Helpers\PurchaseHelper;
use App\Services\PurchaseService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
class PurchaseDetails extends Component
{
    use LivewireOperations;

    public $id,$purchase;
    public $refundedQty = 0;

    #[Url]
    public $activeTab = 'details';

    private $purchaseService;

    public $currentItem;

    function boot() {
        $this->purchaseService = app(PurchaseService::class);
    }

    function mount() {
        $this->purchase = $this->purchaseService->find($this->id);
    }

    function setCurrentItem($itemId) {
        $this->currentItem = $this->purchase->purchaseItems()->find($itemId);
    }

    function purchaseCalculations() {
        $totalItems = $this->purchase->items_total_amount;
        $totalExpenses = $this->purchase->expenses_total_amount;
        $orderSubTotal = PurchaseHelper::calcSubtotal($totalItems,$totalExpenses);
        $orderDiscountAmount = PurchaseHelper::calcDiscount($orderSubTotal,$this->purchase->discount_type,$this->purchase->discount_value);
        $orderTotalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($orderSubTotal,$orderDiscountAmount);
        $orderTaxAmount = PurchaseHelper::calcTax($orderTotalAfterDiscount,$this->purchase->tax_percentage);
        $orderGrandTotal = PurchaseHelper::calcGrandTotal($orderTotalAfterDiscount,$orderTaxAmount);

        return [
            'orderSubTotal' => $orderSubTotal,
            'orderDiscountAmount' => $orderDiscountAmount,
            'orderTotalAfterDiscount' => $orderTotalAfterDiscount,
            'orderTaxAmount' => $orderTaxAmount,
            'orderGrandTotal' => $orderGrandTotal,
        ];
    }

    function refundPurchaseItem() {
        if(!$this->validator([
            'refundedQty' => $this->refundedQty
        ],[
            'refundedQty' => 'required|numeric|min:1|max:'.$this->currentItem->actual_qty
        ])) return;

        $this->purchaseService->refundPurchaseItem($this->currentItem?->id,$this->refundedQty); // TODO : if all products with qty is refunded then change purchase status to refunded and add badge of purchase list order and details page

        $this->mount();

        $this->dismiss();

        $this->popup('success','Purchase item refunded successfully.');

        $this->reset('refundedQty','currentItem');
    }

    public function render()
    {
        $actualQty = $this->purchase->purchaseItems->sum(fn($q)=>$q->actual_qty);
        list($orderSubTotal,$orderDiscountAmount,$orderTotalAfterDiscount,$orderTaxAmount,$orderGrandTotal) = array_values($this->purchaseCalculations());
        return view('livewire.admin.purchases.purchase-details',get_defined_vars());
    }
}
