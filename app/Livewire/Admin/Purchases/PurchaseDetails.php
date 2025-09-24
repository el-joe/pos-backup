<?php

namespace App\Livewire\Admin\Purchases;

use App\Helpers\PurchaseHelper;
use App\Services\PurchaseService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
class PurchaseDetails extends Component
{
    public $id,$purchase;

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


    public function render()
    {
        $actualQty = $this->purchase->purchaseItems->sum(fn($q)=>$q->actual_qty);
        list($orderSubTotal,$orderDiscountAmount,$orderTotalAfterDiscount,$orderTaxAmount,$orderGrandTotal) = array_values($this->purchaseCalculations());
        return view('livewire.admin.purchases.purchase-details',get_defined_vars());
    }
}
