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
use App\Models\Tenant\User;
use App\Repositories\PurchaseRepository;

class PurchaseService
{
    public function __construct(private PurchaseRepository $repo,private ExpenseCategoryService $expenseCategoryService, private StockService $stockService,private TransactionService $transactionService) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function activeList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter + [
            'active' => 1
        ], $perPage, $orderByDesc);
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
            $purchase = $this->repo->find($id);
        }else{
            $purchase = new Purchase();
        }

        // fill purchase data
        $status = PurchaseStatusEnum::from($data['payment_status'] ?? 'pending')->value;
        $purchase->fill([
            'supplier_id' => $data['supplier_id'],
            'branch_id' => $data['branch_id'],
            'ref_no' => $data['ref_no'],
            'order_date' => $data['order_date'],
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'tax_id' => $data['tax_id'],
            'tax_percentage' => $data['tax_rate'] ?? $data['tax_percentage'] ?? 0,
            // 'paid_amount' => $status == 'full_paid' ? $data['grand_total'] : ($data['payment_amount'] ?? $data['paid_amount'] ?? 0),
            'paid_amount' => 0,
            'status' => $status,
        ])->save();

        // fill purchase items data
        $purchase->purchaseItems()->delete();
        foreach ($data['orderProducts'] as $item) {
            $purchase->purchaseItems()->create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['id'],
                'unit_id' => $item['unit_id'],
                'qty' => $item['qty'],
                'purchase_price' => $item['purchase_price'],
                'discount_percentage' => $item['discount_percentage'],
                'tax_percentage' => $item['tax_percentage'],
                'x_margin' => $item['x_margin'],
                'sell_price' => $item['sell_price'],
            ]);
        }

        // fill expenses data
        $defaultExpenseCategory = $this->expenseCategoryService->getDefaultCategory('purchase');
        if(!$defaultExpenseCategory){
            $defaultExpenseCategory = $this->expenseCategoryService->save(null,[
                'name' => 'purchase',
                'default' => 1,
            ]);
        }
        foreach ($data['expenses'] as $item) {
            $purchase->expenses()->create([
                'branch_id' => $data['branch_id'],
                'model_type' => Purchase::class,
                'model_id' => $purchase->id,
                'expense_category_id' => $defaultExpenseCategory?->id,
                'amount' => $item['amount'],
                'note' => $item['description'],
                'expense_date' => $item['expense_date'],
            ]);
        }

        // fill stock data
        foreach ($data['orderProducts'] as $item) {
            // add Stock
            $this->stockService->addStock(productId: $item['id'],unitId: $item['unit_id'],qty: $item['qty'],sellPrice: $item['sell_price'],unitCost: $item['purchase_price'],branchId: $data['branch_id']);
        }
        // fill purchase payments data
        // Grouped by type = Purchase Invoice
        $transactionData = [
            'description' => 'Purchase Payment for #'.$purchase->ref_no,
            'type' => TransactionTypeEnum::PURCHASE_INVOICE->value,
            'reference_type' => Purchase::class,
            'reference_id' => $purchase->id,
            'branch_id' => $purchase->branch_id,
            'note' => $data['payment_note'] ?? '',
            'amount' => $data['grand_total'] ?? 0,
            'lines' => $this->purchaseInvoiceLines($data,'create')
        ];

        $this->transactionService->create($transactionData);

        if(($data['payment_status'] ?? 'pending') == 'pending'){
            return $purchase;
        }
        // Grouped by type = Payments
        $this->addPayment($purchase->id, $data);
    }

    function addPayment($purchaseId, $data , $reverse = false) {
        $purchase = $this->repo->find($purchaseId);
        if(!$purchase) return;
        $transactionData = [
            'description' => ($reverse ? 'Refund ' : '').'Purchase Payment for #'.$purchase->ref_no,
            'type' => $reverse ? TransactionTypeEnum::PURCHASE_PAYMENT_REFUND->value : TransactionTypeEnum::PURCHASE_PAYMENT->value,
            'reference_type' => Purchase::class,
            'reference_id' => $purchase->id,
            'branch_id' => $purchase->branch_id,
            'note' => $data['payment_note'] ?? '',
            'amount' => $data['payment_status'] == 'full_paid' ? ($data['grand_total'] ?? 0) : ($data['payment_amount'] ?? 0),
            'lines' => $this->purchasePaymentLines($data,'create',$reverse)
        ];

        $this->transactionService->create($transactionData);
        if(!$reverse){
            $purchase->increment('paid_amount', $data['payment_status'] == 'full_paid' ? ($data['grand_total'] ?? 0) : ($data['payment_amount'] ?? 0));
        }

        $purchase = $purchase->refresh();

        $purchaseDue = $purchase->due_amount;
        $total = $purchase->total_amount;

        if($purchaseDue <= 0){
            $purchase->update(['status' => PurchaseStatusEnum::FULL_PAID->value]);
        }elseif($purchaseDue > 0 && $purchaseDue < $total){
            $purchase->update(['status' => PurchaseStatusEnum::PARTIAL_PAID->value]);
        }elseif($purchaseDue == $total){
            $purchase->update(['status' => PurchaseStatusEnum::PENDING->value]);
        }
    }

    function purchaseInvoiceLines($data,$event = 'create',$reverse = false) { // $reverse mean refund
        // -------------------------- Purchase entry --------------------------------

        // Debit Inventory (for goods purchased)
        $inventoryLine = $this->createInventoryLine($data , $reverse);

        // Debit Expense (for additional purchase-related expenses like shipping, handling, etc.)
        $expenseLine = $this->createExpenseLine($data, $reverse);

        // Debit VAT Receivable (input tax you can claim from tax authority)
        $vatReceivableLine = $this->createVatReceivableLine($data, $reverse);

        // Credit Purchase Discount (reduces cost if supplier gave discount)
        $purchaseDiscountLine = $this->createPurchaseDiscountLine($data, $reverse);

        // Credit Supplier (record liability to supplier for total amount owed)
        $supplierCreditLine = $this->createSupplierCreditLine($data, $reverse);


        return [
            // Purchase entry --------------------------------
            $inventoryLine,         // DR Inventory (record goods in stock)
            $expenseLine,           // DR Expense (record additional expenses)
            $vatReceivableLine,     // DR VAT Receivable (input tax asset)
            $purchaseDiscountLine,  // CR Purchase Discount (contra expense)
            $supplierCreditLine,    // CR Supplier (accounts payable)
        ];
    }

    function purchasePaymentLines($data,$event = 'create' ,$reverse = false) {
        // ------------------------- Payment entry --------------------------------
        // 3 status (pending, partial_paid, full_paid)
        if(($data['payment_status'] ?? 'pending') == 'pending'){
            return;
        }else{
            // Credit Branch Cash (if you paid now – reduce your cash balance)
            $branchCashLine = $this->createBranchCashLine($data,$data['payment_status'] ?? 'full_paid', $reverse);

            // Debit Supplier (reduce liability when you make payment to supplier)
            $supplierDebitLine = $this->createSupplierDebitLine($data,$data['payment_status'] ?? 'full_paid', $reverse);
        }

        return [
            // Payment entry --------------------------------
            $branchCashLine,        // CR Branch Cash (if payment is made)
            $supplierDebitLine,     // DR Supplier (reduce payable by paid amount)
        ];
    }

    function createInventoryLine($data,$reverse = false) {
        $getInventoryAccount = Account::default('Inventory',AccountTypeEnum::INVENTORY->value,$data['branch_id']);

        if(!isset($data['orderProducts']) || !is_array($data['orderProducts'])) {
            return false;
        }
        // get sub total from order products = product qty * purchase price
        $subTotal = array_sum(array_map(function($item) {
            return (float)$item['qty'] * (float)($item['purchase_price']);
        }, $data['orderProducts']));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getInventoryAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $subTotal,
        ];
    }

        function createCogsLine($data,$reverse = false) {
            $getInventoryAccount = Account::default('Inventory',AccountTypeEnum::INVENTORY->value,$data['branch_id']);

            if(!isset($data['orderProducts']) || !is_array($data['orderProducts'])) {
                return false;
            }
            // get sub total from order products = product qty * purchase price
            $subTotal = array_sum(array_map(function($item) {
                return (float)$item['qty'] * (float)($item['purchase_price']);
            }, $data['orderProducts']));

            //`transaction_id`, `account_id`, `type`, `amount`
            return [
                'account_id' => $getInventoryAccount->id,
                'type' => $reverse ? 'credit' : 'debit',
                'amount' => $subTotal,
            ];
        }


    function createBranchCashLine($data,$type = 'full_paid' ,$reverse = false) {
        $getBranchCashAccount = Account::default('Branch Cash',AccountTypeEnum::BRANCH_CASH->value,$data['branch_id']);

        // get paid amount from data
        $paidAmount = $data['grand_total'] ?? $data['payment_amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getBranchCashAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $paidAmount,
        ];
    }

    function deleteExpenseTransaction($id) {
        // -------------------------- Purchase entry --------------------------------

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

        $transactionData = [
            'description' => 'Purchase Refund Expense for #'.$purchaseOrder->ref_no,
            'type' => TransactionTypeEnum::PURCHASE_INVOICE_REFUND->value,
            'reference_type' => Purchase::class,
            'reference_id' => $purchaseOrder->id,
            'branch_id' => $purchaseOrder->branch_id,
            'note' => 'Refunded for purchase Expense #'. $expense->id,
            'amount' => $expense->amount ?? 0,
            'lines' => $this->purchaseInvoiceLines($refundInvoiceData,'create',true)
        ];

        $this->transactionService->create($transactionData);

        // refund purchase payments
        $purchaseDueAmount = $purchaseOrder->due_amount;
        $totalRefunded = $grandTotal - $purchaseDueAmount;
        if($totalRefunded > 0){
            $refundPaymentData = [
                'grand_total' => $totalRefunded,
                'payment_note' => 'Refund Purchase Expense #'. $expense->id,
                'payment_status' => 'refunded',
                'payment_amount' => $totalRefunded,
                'branch_id' => $purchaseOrder->branch_id,
                'supplier_id' => $purchaseOrder->supplier_id,
            ];

            $this->addPayment($purchaseOrder->id, $refundPaymentData , true);

            $purchaseOrder->decrement('paid_amount', $totalRefunded);
        }

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

    function createExpenseLine($data,$reverse = false) {
        $getExpenseAccount = Account::default('Expense',AccountTypeEnum::EXPENSE->value,$data['branch_id']);
        // get total expenses from data
        $totalExpenses = array_sum(array_column($data['expenses'] ?? [],'amount'));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getExpenseAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $totalExpenses ?? 0,
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
        ]);        // get tax amount from data
        $taxAmount = $data['tax_amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getVatReceivableAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $taxAmount,
        ];
    }

    function createPurchaseDiscountLine($data,$reverse = false) {
        $getPurchaseDiscountAccount = Account::firstOrCreate([
            'name' => 'Purchase Discount',
            'code' => 'Purchase Discount',
            'model_type' => Branch::class,
            'model_id' => $data['branch_id'],
            'type' => AccountTypeEnum::PURCHASE_DISCOUNT->value,
            'branch_id' => $data['branch_id'],
            'active' => 1,
        ]);        // get discount amount from data
        $discountAmount = $data['discount_amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getPurchaseDiscountAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $discountAmount,
        ];
    }

    function createSupplierCreditLine($data,$reverse = false) {
        if(!isset($data['payment_account'])){
            $getSupplierAccount = User::find($data['supplier_id'])->accounts->first();
        }else{
            $getSupplierAccount = Account::find($data['payment_account']);
        }

        // get grand total from data
        $grandTotal = $data['grand_total'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getSupplierAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $grandTotal,
        ];
    }

    function createSupplierDebitLine($data,$type = 'full_paid', $reverse = false) {
        if(!isset($data['payment_account'])){
            $getSupplierAccount = User::find($data['supplier_id'])->accounts->first();
        }else{
            $getSupplierAccount = Account::find($data['payment_account']);
        }

        // get paid amount from data
        if($type == 'full_paid'){
            $paidAmount = $data['grand_total'] ?? 0;
        }else{
            $paidAmount = $data['payment_amount'] ?? 0;
        }

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getSupplierAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $paidAmount,
        ];
    }

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
            'type' => TransactionTypeEnum::PURCHASE_INVOICE_REFUND->value,
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
