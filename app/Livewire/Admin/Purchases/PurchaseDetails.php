<?php

namespace App\Livewire\Admin\Purchases;

use App\Helpers\PurchaseHelper;
use App\Models\Tenant\Expense;
use App\Models\Tenant\PurchaseItem;
use App\Services\CashRegisterService;
use App\Services\ExpenseService;
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

    private $purchaseService, $expenseService, $cashRegisterService;

    public $currentItem,$currentExpense;

    function boot() {
        $this->purchaseService = app(PurchaseService::class);
        $this->expenseService = app(ExpenseService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    function mount() {
        $this->purchase = $this->purchaseService->find($this->id);
    }

    function setCurrentItem($itemId) {
        $this->currentItem = $this->purchase->purchaseItems()->find($itemId);
    }

    function setCurrentExpense($expenseId) {
        $this->currentExpense = $this->purchase->expenses()->find($expenseId);

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

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $getTotalRefunded = $this->getTotalRefunded($this->currentItem->id, $this->refundedQty);
            $this->cashRegisterService->increment($cashRegister->id, 'total_purchase_refunds', $getTotalRefunded);
        }


        $this->purchaseService->refundPurchaseItem($this->currentItem?->id,$this->refundedQty); // TODO : if all products with qty is refunded then change purchase status to refunded and add badge of purchase list order and details page

        $this->mount();

        $this->dismiss();

        $this->popup('success','Purchase item refunded successfully.');

        $this->reset('refundedQty','currentItem');
    }

    function getTotalRefunded($purchaseItemId, $qty) {
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

    function deleteExpenseConfirm($id) {
        $this->setCurrentExpense($id);
        $this->confirm('deleteExpense','error','Delete Expense','Are you sure you want to delete this expense? This action cannot be undone.','Do it!' );
    }

    function deleteExpense() {
        $expense = $this->currentExpense;
        if(!$expense) {
            $this->popup('error','Expense not found.');
            return;
        }

        if($expense->refunded) {
            $this->popup('error','Expense already refunded.');
            return;
        }

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $getTotalRefunded = $this->getTotalExpenseRefunded($expense->id);
            $this->cashRegisterService->increment($cashRegister->id, 'total_purchase_refunds', $getTotalRefunded);
        }

        $this->purchaseService->deleteExpenseTransaction($expense->id);
        $this->expenseService->delete($expense->id);

        $this->mount();

        $this->popup('success','Expense deleted successfully.');
    }

    function getTotalExpenseRefunded($id){
        $expense = Expense::find($id);
        $purchaseOrder = $expense?->model;

        $discountAmount = PurchaseHelper::calcDiscount($expense->amount, $purchaseOrder->discount_type , $purchaseOrder->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($expense->amount, $discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount, $purchaseOrder->tax_percentage ?? 0);
        $grandTotal = PurchaseHelper::calcGrandTotal($totalAfterDiscount,$taxAmount);

        // reverse purchase invoice type transaction
        $refundInvoiceData = [
            'branch_id' => $purchaseOrder->branch_id,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'supplier_id' => $purchaseOrder->supplier_id,
            'grand_total' => $grandTotal,
            'expenses' => [
                [
                    'amount' => $expense->amount,
                ]
            ]
        ];

        // refund purchase payments
        $purchaseDueAmount = $purchaseOrder->due_amount;
        $totalRefunded = $grandTotal - $purchaseDueAmount;

        return $totalRefunded;
    }

    public function render()
    {
        $actualQty = $this->purchase->purchaseItems->sum(fn($q)=>$q->actual_qty);
        list($orderSubTotal,$orderDiscountAmount,$orderTotalAfterDiscount,$orderTaxAmount,$orderGrandTotal) = array_values($this->purchaseCalculations());
        return view('livewire.admin.purchases.purchase-details',get_defined_vars());
    }
}
