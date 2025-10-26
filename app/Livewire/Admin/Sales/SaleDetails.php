<?php

namespace App\Livewire\Admin\Sales;

use App\Helpers\SaleHelper;
use App\Models\Tenant\SaleItem;
use App\Services\CashRegisterService;
use App\Services\SellService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
class SaleDetails extends Component
{
    use LivewireOperations;

    public $id,$currentItem,$order,$refundedQty = 1;

    private $sellService, $cashRegisterService;

    #[Url]
    public $activeTab = 'details';

    function boot() {
        $this->sellService = app(SellService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    function mount() {
        $this->order = $this->sellService->first($this->id,[
            'saleItems','customer','branch','transactions'
        ]);
    }

    function setCurrentItem($id) {
        $this->currentItem = SaleItem::find($id);
    }

    function refundSaleItem() {
        if(!$this->validator([
            'refundedQty' => $this->refundedQty
        ],[
            'refundedQty' => 'required|numeric|min:1|max:'.$this->currentItem->actual_qty
        ])) return;

        $this->sellService->refundSaleItem($this->currentItem?->id,$this->refundedQty);

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $totalRefunded = $this->getTotalRefundedCalc($this->currentItem?->id, $this->refundedQty);
            $this->cashRegisterService->increment($cashRegister->id, 'total_sale_refunds', $totalRefunded);
        }

        $this->mount();

        $this->dismiss();

        $this->popup('success','Purchase item refunded successfully.');

        $this->reset('refundedQty','currentItem');
    }

    function getTotalRefundedCalc($saleItemId, $qty) {
        $saleItem = SaleItem::findOrFail($saleItemId);
        $saleOrder = $saleItem->sale;
        $product = $saleItem->toArray();
        $product['qty'] = $qty;
        $discountAmount = SaleHelper::singleDiscountAmount($product,$saleOrder->saleItems, $saleOrder->discount_type, $saleOrder->discount_value, $saleOrder->max_discount_amount ?? 0);
        $taxPercentage = $saleItem->taxable == 1 ? ($saleOrder->tax_percentage ?? 0) : 0;
        $taxAmount = SaleHelper::singleTaxAmount($product,$saleOrder->saleItems, $saleOrder->discount_type, $saleOrder->discount_value,$taxPercentage, $saleOrder->max_discount_amount ?? 0);
        // -----------------------------------
        $grandTotalFromRefundedQty = SaleHelper::singleGrandTotal($product,$saleOrder->saleItems, $saleOrder->discount_type, $saleOrder->discount_value, $taxPercentage, $saleOrder->max_discount_amount ?? 0);
        $dueAmount = $saleOrder->due_amount;
        $totalRefunded = $grandTotalFromRefundedQty - $dueAmount;

        return numFormat($totalRefunded,3);
    }

    function calcTotals() {

        $itemsCount = $this->order->saleItems->count();
        $totalQty = $this->order->saleItems->sum(fn($q)=>$q->qty - $q->refunded_qty);
        $subTotal = $this->order->subTotal;
        $totalDiscount = $this->order->discountAmount;
        $totalTax = $this->order->taxAmount;
        $grandTotal = numFormat($this->order->grandTotalAmount, 1);
        $paid = numFormat($this->order->paid_amount, 1);
        $due = $grandTotal - $paid;
        return get_defined_vars();
    }

    public function render()
    {

        if(!$this->order){
            abort(404);
        }

        extract($this->calcTotals());

        return view('livewire.admin.sales.sale-details',get_defined_vars());
    }
}
