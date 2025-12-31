<?php

namespace App\Livewire\Admin\Refunds;

use App\Enums\AuditLogActionEnum;
use App\Helpers\PurchaseHelper;
use App\Helpers\SaleHelper;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Product;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\Refund;
use App\Models\Tenant\RefundItem;
use App\Models\Tenant\Sale;
use App\Models\Tenant\SaleItem;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\ProductService;
use App\Services\PurchaseService;
use App\Services\SellService;
use App\Traits\LivewireOperations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

class AddRefund extends Component
{
    use LivewireOperations;


    #[Url]
    public ?string $order_type = null;

    #[Url]
    public ?int $order_id = null;

    public $data = [];
    public $refundItems = [];

    protected $sellService;
    protected $purchaseService;
    protected $cashRegisterService;

    function boot(){
        $this->sellService = app(SellService::class);
        $this->purchaseService = app(PurchaseService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    function resolveOrderModel()
    {
        return match ($this->order_type) {
            'sale', Sale::class => new Sale(),
            'purchase', Purchase::class => new Purchase(),
            default => null
        };
    }

    function getOrder(){
        $orderModel = $this->resolveOrderModel();
        if($orderModel == null) return null;
        return $orderModel::query()
                ->where('id', $this->order_id)
                ->first();
    }

    function mount(){
        if($this->order_type && $this->order_id){
            $order = $this->getOrder();
            if($order){
                $this->data['branch_id'] = $order->branch_id;
            }
        }
    }

    function updatingOrderType(){
        $this->reset('refundItems');
    }

    function updatingOrderId(){
        $this->reset('refundItems');
    }

    function updatingDataBranchId(){
        $this->reset('refundItems');
    }

    function saveRefund(){
        $this->data['order_type'] = $this->order_type;
        $this->data['order_id'] = $this->order_id;

        if(!$this->validator($this->data,[
            'branch_id' => 'required|exists:branches,id',
            'order_type' => 'required|in:sale,purchase',
            'order_id' => 'required|integer',
            'reason' => 'nullable|string|max:255',
        ]))return;

        if(!$this->validator([
            'refundItems' => $this->refundItems
        ],[
            'refundItems' => 'required|array|min:1',
            'refundItems.*' => 'required|integer|min:1',
        ]))return;

        foreach ($this->refundItems??[] as $itemId => $qty) {
            $orderItem = $this->getOrder()->{$this->order_type == 'sale' ? 'saleItems' : 'purchaseItems'}()->where('id',$itemId)->first();
            if(($orderItem->actual_qty ?? 0) == 0){
                $this->alert('error', __('general.messages.invalid_refund_quantity', ['item' => $orderItem->product->name ?? '']));
                return;
            }
        }

        DB::beginTransaction();
        try {
            $data = $this->data;
            $data['order_type'] = $this->order_type == 'sale' ? Sale::class : Purchase::class;
            $refund = Refund::create($data);
            foreach($this->refundItems as $itemId => $qty){
                if($qty == 0){
                    continue;
                }
                $orderItem = $this->getOrder()->{$this->order_type == 'sale' ? 'saleItems' : 'purchaseItems'}()->where('id',$itemId)->first();
                if(!$orderItem){
                    throw new \Exception('Invalid order item selected for refund.');
                }
                RefundItem::create([
                    'refund_id' => $refund->id,
                    'product_id' => $orderItem->product_id,
                    'unit_id' => $orderItem->unit_id,
                    'qty' => $qty,
                    'refundable_type' => get_class($orderItem),
                    'refundable_id' => $orderItem->id,
                ]);
            }
            if($this->order_type === 'sale'){
                $this->refundSaleItem($refund->id);
            } elseif($this->order_type === 'purchase'){
                $this->refundPurchaseItem($refund->id);
            }

            $this->alert('success', __('general.messages.created_successfully', ['type' => __('general.pages.refunds.refund')]));
            $this->redirectWithTimeout(route('admin.refunds.list'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', $e->getMessage());
        }
    }

    function refundSaleItem($refundId) {
        foreach($this->refundItems as $id => $qty){
            if($qty == 0){
                continue;
            }
            $this->sellService->refundSaleItem($id,$qty);
        }

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $totalRefunded = 0;
            foreach($this->refundItems as $id => $qty){
                if($qty == 0){
                    continue;
                }

                $totalRefunded += $this->getSaleTotalRefundedCalc($id, $qty);
            }
            $this->cashRegisterService->increment($cashRegister->id, 'total_sale_refunds', $totalRefunded);
        }

        AuditLog::log(AuditLogActionEnum::RETURN_SALE_ORDER, ['order_id' => $refundId, 'route' => route('admin.refunds.list')]);
    }

    function getSaleTotalRefundedCalc($saleItemId, $qty) {
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

    function refundPurchaseItem($refundId) {

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $getTotalRefunded = 0;
            foreach($this->refundItems as $id => $qty){
                if($qty == 0){
                    continue;
                }
                $getTotalRefunded += $this->getPurchaseTotalRefunded($id, $qty);
            }
            $this->cashRegisterService->increment($cashRegister->id, 'total_purchase_refunds', $getTotalRefunded);
        }

        foreach($this->refundItems as $id => $qty){
            if($qty == 0){
                continue;
            }
            $this->purchaseService->refundPurchaseItem($id,$qty);
        }

        AuditLog::log(AuditLogActionEnum::RETURN_PURCHASE_ORDER, ['order_id' => $refundId, 'route' => route('admin.refunds.list')]);
    }

    function getPurchaseTotalRefunded($purchaseItemId, $qty) {
        $purchaseItem = PurchaseItem::findOrFail($purchaseItemId);
        $purchaseOrder = $purchaseItem->purchase;
        $refundedQtyAmount = $purchaseItem->unit_amount_after_tax * $qty;
        $discountAmount = PurchaseHelper::calcDiscount($refundedQtyAmount, $purchaseOrder->discount_type , $purchaseOrder->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($refundedQtyAmount, $discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount, $purchaseOrder->tax_percentage ?? 0);
        // -----------------------------------
        $grandTotalFromRefundedQty = PurchaseHelper::calcGrandTotal($totalAfterDiscount,$taxAmount);
        $purchaseDueAmount = $purchaseOrder->due_amount;
        $totalRefunded = $grandTotalFromRefundedQty - $purchaseDueAmount;
        return $totalRefunded;
    }

    public function render()
    {
        $branches = app(BranchService::class)->activeList();
        $orders = [];
        if($this->order_type){
            $orderModel = $this->resolveOrderModel();
            $orders = $orderModel::query()->select('id')->get();
        }

        $order = $this->getOrder();


        return layoutView('refunds.add-refund', get_defined_vars())
            ->title(__('general.titles.add_refund'));
    }
}
