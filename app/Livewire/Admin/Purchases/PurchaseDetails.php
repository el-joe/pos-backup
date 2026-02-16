<?php

namespace App\Livewire\Admin\Purchases;

use App\Enums\AuditLogActionEnum;
use App\Helpers\PurchaseHelper;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Expense;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\TransactionLine;
use App\Services\CashRegisterService;
use App\Services\ExpenseService;
use App\Services\PurchaseService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

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

        $getTotalRefunded = $this->getTotalRefunded($this->currentItem->id, $this->refundedQty);

        if($cashRegister){
            $this->cashRegisterService->increment($cashRegister->id, 'total_purchase_refunds', $getTotalRefunded);
        }


        $this->purchaseService->refundPurchaseItem($this->currentItem?->id,$this->refundedQty); // TODO : if all products with qty is refunded then change purchase status to refunded and add badge of purchase list order and details page

        AuditLog::log(AuditLogActionEnum::REFUND_PURCHASE_ITEM, ['id' => $this->currentItem->id, 'purchase_id' => $this->purchase->id]);

        $purchaseForNotify = $this->purchase->loadMissing(['branch','supplier']);
        superAdmins()->each(function(\App\Models\Tenant\Admin $admin) use ($purchaseForNotify, $getTotalRefunded){
            $admin->notifyPurchaseItemRefunded($purchaseForNotify, $getTotalRefunded);
        });

        $this->mount();

        $this->dismiss();

        $this->popup('success', __('general.messages.purchase_item_refunded_successfully'));

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

        AuditLog::log(AuditLogActionEnum::DELETE_EXPENSE_INTO_PURCHASE_TRY, ['id' => $id, 'purchase_id' => $this->purchase->id]);

        $this->confirm(
            'deleteExpense',
            'error',
            __('general.messages.delete_expense_title'),
            __('general.messages.delete_expense_confirmation'),
            __('general.messages.do_it')
        );
    }

    function deleteExpense() {
        $expense = $this->currentExpense;
        if(!$expense) {
            $this->popup('error', __('general.messages.expense_not_found'));
            return;
        }

        if($expense->refunded) {
            $this->popup('error', __('general.messages.expense_already_refunded'));
            return;
        }

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $getTotalRefunded = $this->getTotalExpenseRefunded($expense->id);
            $this->cashRegisterService->increment($cashRegister->id, 'total_purchase_refunds', $getTotalRefunded);
        }

        $this->purchaseService->deleteExpenseTransaction($expense->id);
        $this->expenseService->delete($expense->id);

        AuditLog::log(AuditLogActionEnum::DELETE_EXPENSE_INTO_PURCHASE, ['id' => $expense->id, 'purchase_id' => $this->purchase->id]);

        $this->mount();

        $this->popup('success', __('general.messages.expense_deleted_successfully'));
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

    function purchaseTransactionLines(){
        return  TransactionLine::with(['transaction','account','transaction.branch'])
            ->whereHas('transaction', function($query){
                $query->where('reference_type', Purchase::class)
                      ->where('reference_id', $this->purchase->id);
            })
            ->orderByDesc('transaction_id')
            ->orderByDesc('id')
            ->get()
            ->map(function($line) {
                return (object)[
                    'id' => $line->id,
                    'transaction_id' => $line->transaction_id,
                    'type' => $line->transaction?->type?->label(),
                    'branch' => $line->transaction?->branch?->name ?? 'N/A',
                    'reference' => $line->ref,
                    'note' => $line->transaction?->note,
                    'date' => dateTimeFormat($line->transaction?->date, true, false),
                    'account' => $line->account?->paymentMethod?->name . ' - ' . ($line->account?->name ?? 'N/A'),
                    'line_type' => $line->type,
                    'amount' => currencyFormat($line->amount, true),
                    'amount_raw' => $line->amount,
                    'created_at' => dateTimeFormat($line->created_at),
                ];
            });
    }

    public function render()
    {
        $actualQty = $this->purchase->purchaseItems->sum(fn($q)=>$q->actual_qty);
        list($orderSubTotal,$orderDiscountAmount,$orderTotalAfterDiscount,$orderTaxAmount,$orderGrandTotal) = array_values($this->purchaseCalculations());

        $transactionLines = $this->purchaseTransactionLines();

        return layoutView('purchases.purchase-details', get_defined_vars());
    }
}
