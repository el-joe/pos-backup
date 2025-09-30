<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\PurchaseStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Helpers\PurchaseHelper;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Expense;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\Sale;
use App\Models\Tenant\User;
use App\Repositories\SellRepository;

class SellService
{
    public function __construct(private SellRepository $repo,private StockService $stockService,private TransactionService $transactionService) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function first($id = null, $relations = [])
    {
        return $this->repo->first($relations,[
            'id' => $id
        ]);
    }


    function save($id = null,$data) {
        if($id) {
            $sell = $this->repo->find($id);
        }else{
            $sell = new Sale();
        }

        // fill purchase data
        $sell->fill([
            'customer_id' => $data['customer_id'],
            'branch_id' => $data['branch_id'],
            'invoice_number' => $data['invoice_number'],
            'order_date' => $data['order_date'],
            'tax_id' => $data['tax_id'] ?? null,
            'tax_percentage' => $data['tax_percentage'] ?? 0,
            'discount_id' => $data['discount_id'] ?? null,
            'discount_type' => $data['discount_type'] ?? null,
            'discount_value' => $data['discount_value'] ?? 0,
            'paid_amount' => 0
        ])->save();

        // fill sale items data
        $sell->saleItems()->delete();
        foreach ($data['products'] as $item) {
            $sell->saleItems()->create([
                'sale_id' => $sell->id,
                'product_id' => $item['id'],
                'unit_id' => $item['unit_id'],
                'qty' => $item['qty'] ?? $item['quantity'],
                'taxable' => $item['taxable'] ?? 0,
                'unit_cost' => $item['unit_cost'] ?? 0,
                'sell_price' => $item['sell_price'] ?? 0
            ]);
        }

        // fill stock data
        foreach ($data['products'] as $item) {
            // add Stock
            $this->stockService->removeFromStock(productId: $item['id'],unitId: $item['unit_id'],qty: ($item['qty']??$item['quantity']),branchId: $data['branch_id']);
        }
        // fill purchase payments data
        // Grouped by type = Purchase Invoice
        $transactionData = [
            'description' => 'Sale Payment for #'.$sell->invoice_number,
            'type' => TransactionTypeEnum::SALE_INVOICE->value,
            'reference_type' => Sale::class,
            'reference_id' => $sell->id,
            'branch_id' => $sell->branch_id,
            'note' => $data['payment_note'] ?? '',
            'amount' => $data['payment_amount'] ?? 0,
            'lines' => $this->saleInvoiceLines($data,'create')
        ];

        $this->transactionService->create($transactionData);

        if(count($data['payments']??[])){
            return $sell;
        }
        // Grouped by type = Payments
        $this->addPayment($sell->id, $data);
    }

    function addPayment($sellId, $data , $reverse = false) {
        $sell = $this->repo->find($sellId);
        if(!$sell) return;
        $transactionData = [
            'description' => ($reverse ? 'Refund ' : '').'Sale Payment for #'.$sell->invoice_number,
            'type' => $reverse ? TransactionTypeEnum::SALE_REFUND->value : TransactionTypeEnum::SALE_PAYMENT->value,
            'reference_type' => Sale::class,
            'reference_id' => $sell->id,
            'branch_id' => $sell->branch_id,
            'note' => $data['payment_note'] ?? '',
            'amount' => $data['payment_amount'] ?? 0,
            'lines' => $this->salePaymentLines($data,'create',$reverse)
        ];

        $this->transactionService->create($transactionData);
        if(!$reverse){
            $sell->increment('paid_amount', $data['payment_amount'] ?? 0);
        }

        return $sell->refresh();
    }

    function saleInvoiceLines($data,$event = 'create',$reverse = false) { // $reverse mean refund
        // -------------------------- Purchase entry --------------------------------

        // Debit Inventory (for goods purchased)
        $inventoryLine = $this->createInventoryLine($data , $reverse);

        // Debit Expense (for additional purchase-related expenses like shipping, handling, etc.)
        // $expenseLine = $this->createExpenseLine($data, $reverse);

        // Debit VAT Receivable (input tax you can claim from tax authority)
        $vatReceivableLine = $this->createVatReceivableLine($data, $reverse);

        // Credit Sale Discount (reduces cost if supplier gave discount)
        $saleDiscountLine = $this->createSaleDiscountLine($data, $reverse);

        // Credit Customer (record liability to customer for total amount owed)
        $customerCreditLine = $this->createCustomerDebitLine($data, $reverse);


        return [
            // Purchase entry --------------------------------
            $inventoryLine,         // DR Inventory (record goods in stock)
            // $expenseLine,           // DR Expense (record additional expenses)
            $vatReceivableLine,     // DR VAT Receivable (input tax asset)
            $saleDiscountLine,      // CR Sale Discount (contra expense)
            $customerCreditLine,    // CR Customer (accounts receivable)
        ];
    }

    function salePaymentLines($data,$event = 'create' ,$reverse = false) {
        // ------------------------- Payment entry --------------------------------
        foreach ($data['payments'] as $payment) {
            $payment['branch_id'] = $data['branch_id'];
            $branchCashLine = $this->createBranchCashLine($payment,'partial_paid', $reverse);

            $customerCreditLine = $this->createCustomerCreditLine($payment,'partial_paid', $reverse);
        }

        return [
            // Payment entry --------------------------------
            $branchCashLine,        // CR Branch Cash (if payment is made)
            $customerCreditLine,     // CR Customer (reduce receivable by paid amount)
        ];
    }

    function createInventoryLine($data,$reverse = false) {
        $getInventoryAccount = Account::firstOrCreate([
            'name' => 'Inventory',
            'code' => 'inventory',
            'model_type' => Branch::class,
            'model_id' => $data['branch_id'],
            'type' => AccountTypeEnum::INVENTORY->value,
            'branch_id' => $data['branch_id'],
            'active' => 1,
        ]);
        if(!isset($data['products']) || !is_array($data['products'])) {
            return false;
        }
        // get sub total from order products = product qty * unit cost
        $subTotal = array_sum(array_map(function($item) {
            return (float)($item['qty']??$item['quantity']) * (float)$item['unit_cost'];
        }, $data['products']));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getInventoryAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $subTotal,
        ];
    }

    function createBranchCashLine($data,$type = 'full_paid' ,$reverse = false) {
        $getBranchCashAccount = Account::firstOrCreate([
            'name' => 'Branch Cash',
            'code' => 'Branch Cash',
            'model_type' => Branch::class,
            'model_id' => $data['branch_id'],
            'type' => AccountTypeEnum::BRANCH_CASH->value,
            'branch_id' => $data['branch_id'],
            'active' => 1,
        ]);

        $paidAmount = $data['amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getBranchCashAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $paidAmount,
        ];
    }

    function createVatReceivableLine($data,$reverse = false) {
        $getVatReceivableAccount = Account::firstOrCreate([
            'name' => 'Vat Receivable',
            'code' => 'Vat Receivable',
            'model_type' => Branch::class,
            'model_id' => $data['branch_id'],
            'type' => AccountTypeEnum::VAT_RECEIVABLE->value,
            'branch_id' => $data['branch_id'],
            'active' => 1,
        ]);
        // get tax amount from data
        $taxAmount = $data['tax_amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getVatReceivableAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $taxAmount,
        ];
    }

    function createSaleDiscountLine($data,$reverse = false) {
        $getSaleDiscountAccount = Account::firstOrCreate([
            'name' => 'Sale Discount',
            'code' => 'Sale Discount',
            'model_type' => Branch::class,
            'model_id' => $data['branch_id'],
            'type' => AccountTypeEnum::SALES_DISCOUNT->value,
            'branch_id' => $data['branch_id'],
            'active' => 1,
        ]);
        // get discount amount from data
        $discountAmount = $data['discount_amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getSaleDiscountAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $discountAmount,
        ];
    }

    function createCustomerDebitLine($data,$reverse = false) {
        if(!isset($data['payment_account'])){
            $getCustomerAccount = User::find($data['customer_id'])->accounts->first();
        }else{
            $getCustomerAccount = Account::find($data['payment_account']);
        }

        // get grand total from data
        $grandTotal = $data['grand_total'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getCustomerAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $grandTotal,
        ];
    }

    function createCustomerCreditLine($data,$type = 'full_paid', $reverse = false) {
        $getCustomerAccount = Account::find($data['account_id']);

        $paidAmount = $data['amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getCustomerAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $paidAmount,
        ];
    }

    // TODO
    function refundPurchaseItem($id,$qty) {
        $purchaseItem = PurchaseItem::findOrFail($id);
        $purchaseOrder = $purchaseItem->purchase;
        $refundedQtyAmount = $purchaseItem->unit_amount_after_tax * $qty;
        $discountAmount = PurchaseHelper::calcDiscount($refundedQtyAmount, $purchaseOrder->discount_type , $purchaseOrder->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($refundedQtyAmount, $discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount, $purchaseOrder->tax_percentage ?? 0);
        // -----------------------------------
        $grandTotalFromRefundedQty = PurchaseHelper::calcGrandTotal($totalAfterDiscount,$taxAmount);
        $purchaseDueAmount = $purchaseOrder->due_amount;
        $totalRefunded = $grandTotalFromRefundedQty - $purchaseDueAmount;

        // reverse purchase invoice type transaction
        $refundInvoiceData = [
            'branch_id' => $purchaseOrder->branch_id,
            'orderProducts' => [
                [
                    'qty' => (float)$qty,
                    'purchase_price' => (float)$purchaseItem->unit_amount_after_tax
                ]
            ],
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'supplier_id' => $purchaseOrder->supplier_id,
            'grand_total' => $grandTotalFromRefundedQty

        ];

        $transactionData = [
            'description' => 'Purchase Refund for #'.$purchaseOrder->ref_no,
            'type' => TransactionTypeEnum::PURCHASE_REFUND->value,
            'reference_type' => Purchase::class,
            'reference_id' => $purchaseOrder->id,
            'branch_id' => $purchaseOrder->branch_id,
            'note' => 'Refunded for purchase item #'. ($purchaseItem->product?->name ?? 'N/A'),
            'amount' => $grandTotalFromRefundedQty ?? 0,
            'lines' => $this->purchaseInvoiceLines($refundInvoiceData,'create',true)
        ];

        $this->transactionService->create($transactionData);


        // refund purchase payments
        $totalRefunded = $grandTotalFromRefundedQty - $purchaseDueAmount;
        if($totalRefunded <= 0){
            return;
        }
        $refundPaymentData = [
            'grand_total' => $totalRefunded,
            'payment_note' => 'Refund for purchase item #'. ($purchaseItem->product?->name ?? 'N/A'),
            'payment_status' => 'refunded',
            'payment_amount' => $totalRefunded,
            'branch_id' => $purchaseOrder->branch_id,
            'supplier_id' => $purchaseOrder->supplier_id,
        ];

        $this->addPayment($purchaseOrder->id, $refundPaymentData , true);

        // refund purchase items qty
        $purchaseItem->increment('refunded_qty',$qty);
        $purchaseOrder->decrement('paid_amount',$totalRefunded);

        // Refund Qty from stock
        $this->stockService->reduceStock(productId: $purchaseItem->product_id,unitId: $purchaseItem->unit_id,qty: $qty,branchId: $purchaseOrder->branch_id);

        $purchaseOrder->refresh();

        $purchaseDue = $purchaseOrder->due_amount;
        $total = $purchaseOrder->total_amount;

        if($purchaseDue <= 0){
            $purchaseOrder->update(['status' => PurchaseStatusEnum::FULL_PAID->value]);
        }elseif($purchaseDue > 0 && $purchaseDue < $total){
            $purchaseOrder->update(['status' => PurchaseStatusEnum::PARTIAL_PAID->value]);
        }elseif($purchaseDue == $total){
            $purchaseOrder->update(['status' => PurchaseStatusEnum::PENDING->value]);
        }
    }

    function delete($id) {
        $purchase = $this->repo->find($id);
        if($purchase) {
            return $purchase->delete();
        }

        return false;
    }
}
