<?php

namespace App\Livewire\Admin\Sales;

use App\Helpers\SaleHelper;
use App\Models\Tenant\SaleItem;
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

    private $sellService;

    #[Url]
    public $activeTab = 'details';

    function boot() {
        $this->sellService = app(SellService::class);
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

        $this->mount();

        $this->dismiss();

        $this->popup('success','Purchase item refunded successfully.');

        $this->reset('refundedQty','currentItem');
    }

    function calcTotals() {

        $itemsCount = $this->order->saleItems->count();
        $totalQty = $this->order->saleItems->sum(fn($q)=>$q->qty - $q->refunded_qty) ;
        $subTotal = SaleHelper::subTotal($this->order->saleItems);
        $totalDiscount = SaleHelper::discountAmount($this->order->saleItems,$this->order->discount_type,$this->order->discount_value,$this->order->max_discount_amount);
        $totalTax = SaleHelper::taxAmount($this->order->saleItems,$this->order->discount_type,$this->order->discount_value,$this->order->tax_percentage);
        $grandTotal = SaleHelper::grandTotal($this->order->saleItems,$this->order->discount_type,$this->order->discount_value,$this->order->tax_percentage, $this->order->max_discount_amount);
        $paid = $this->order->paid_amount;
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
