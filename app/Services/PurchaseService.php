<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\PurchaseStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Purchase;
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
            'paid_amount' => $status == 'full_paid' ? $data['grand_total'] : ($data['payment_amount'] ?? $data['paid_amount'] ?? 0),
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
                'description' => $item['description'],
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

        $purchase->refresh();

        $purchseDue = $purchase->due_amount;
        $total = $purchase->total_amount;

        if($purchseDue <= 0){
            $purchase->update(['status' => PurchaseStatusEnum::FULL_PAID->value]);
        }elseif($purchseDue > 0 && $purchseDue < $total){
            $purchase->update(['status' => PurchaseStatusEnum::PARTIAL_PAID->value]);
        }elseif($purchseDue == $total){
            $purchase->update(['status' => PurchaseStatusEnum::PENDING->value]);
        }
    }

    function addPayment($purchaseId, $data) {
        $purchase = $this->repo->find($purchaseId);
        if(!$purchase) return;
        $transactionData = [
            'description' => 'Purchase Payment for #'.$purchase->ref_no,
            'type' => TransactionTypeEnum::PURCHASE_PAYMENT->value,
            'reference_type' => Purchase::class,
            'reference_id' => $purchase->id,
            'branch_id' => $purchase->branch_id,
            'note' => $data['payment_note'] ?? '',
            'amount' => $data['payment_status'] == 'full_paid' ? ($data['grand_total'] ?? 0) : ($data['payment_amount'] ?? 0),
            'lines' => $this->purchasePaymentLines($data,'create')
        ];

        $this->transactionService->create($transactionData);

        $purchase->increment('paid_amount', $data['payment_status'] == 'full_paid' ? ($data['grand_total'] ?? 0) : ($data['payment_amount'] ?? 0));
    }

    function purchaseInvoiceLines($data,$event = 'create') {
        // -------------------------- Purchase entry --------------------------------

        // Debit Inventory (for goods purchased)
        $inventoryLine = $this->createInventoryLine($data);

        // Debit Expense (for additional purchase-related expenses like shipping, handling, etc.)
        $expenseLine = $this->createExpenseLine($data);

        // Debit VAT Receivable (input tax you can claim from tax authority)
        $vatReceivableLine = $this->createVatReceivableLine($data);

        // Credit Purchase Discount (reduces cost if supplier gave discount)
        $purchaseDiscountLine = $this->createPurchaseDiscountLine($data);

        // Credit Supplier (record liability to supplier for total amount owed)
        $supplierCreditLine = $this->createSupplierCreditLine($data);


        return [
            // Purchase entry --------------------------------
            $inventoryLine,         // DR Inventory (record goods in stock)
            $expenseLine,           // DR Expense (record additional expenses)
            $vatReceivableLine,     // DR VAT Receivable (input tax asset)
            $purchaseDiscountLine,  // CR Purchase Discount (contra expense)
            $supplierCreditLine,    // CR Supplier (accounts payable)
        ];
    }

    function purchasePaymentLines($data,$event = 'create') {
        // ------------------------- Payment entry --------------------------------
        // 3 status (pending, partial_paid, full_paid)
        if(($data['payment_status'] ?? 'pending') == 'pending'){
            return;
        }else{
            // Credit Branch Cash (if you paid now – reduce your cash balance)
            $branchCashLine = $this->createBranchCashLine($data,$data['payment_status'] ?? 'full_paid');

            // Debit Supplier (reduce liability when you make payment to supplier)
            $supplierDebitLine = $this->createSupplierDebitLine($data,$data['payment_status'] ?? 'full_paid');
        }

        return [
            // Payment entry --------------------------------
            $branchCashLine,        // CR Branch Cash (if payment is made)
            $supplierDebitLine,     // DR Supplier (reduce payable by paid amount)
        ];
    }

    function createInventoryLine($data) {
        $getInventoryAccount = Account::firstOrCreate([
            'name' => 'Inventory',
            'code' => 'inventory',
            'model_type' => Branch::class,
            'model_id' => $data['branch_id'],
            'type' => AccountTypeEnum::INVENTORY->value,
            'branch_id' => $data['branch_id'],
            'active' => 1,
        ]);
        // get sub total from order products = product qty * purchase price
        $subTotal = array_sum(array_map(function($item) {
            return $item['qty'] * $item['purchase_price'];
        }, $data['orderProducts']));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getInventoryAccount->id,
            'type' => 'debit',
            'amount' => $subTotal,
        ];
    }

    function createBranchCashLine($data,$type = 'full_paid') {
        $getBranchCashAccount = Account::firstOrCreate([
            'name' => 'Branch Cash',
            'code' => 'Branch Cash',
            'model_type' => Branch::class,
            'model_id' => $data['branch_id'],
            'type' => AccountTypeEnum::BRANCH_CASH->value,
            'branch_id' => $data['branch_id'],
            'active' => 1,
        ]);

        // get paid amount from data
        if($type == 'full_paid'){
            $paidAmount = $data['grand_total'] ?? 0;
        }else{
            $paidAmount = $data['payment_amount'] ?? 0;
        }

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getBranchCashAccount->id,
            'type' => 'credit',
            'amount' => $paidAmount,
        ];
    }

    function createExpenseLine($data) {
        $getExpenseAccount = Account::firstOrCreate([
            'name' => 'Expense',
            'code' => 'Expense',
            'model_type' => Branch::class,
            'model_id' => $data['branch_id'],
            'type' => AccountTypeEnum::EXPENSE->value,
            'branch_id' => $data['branch_id'],
            'active' => 1,
        ]);
        // get total expenses from data
        $totalExpenses = array_sum(array_column($data['expenses'] ?? [],'amount'));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getExpenseAccount->id,
            'type' => 'debit',
            'amount' => $totalExpenses,
        ];
    }

    function createVatReceivableLine($data) {
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
            'type' => 'debit',
            'amount' => $taxAmount,
        ];
    }

    function createPurchaseDiscountLine($data) {
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
            'type' => 'credit',
            'amount' => $discountAmount,
        ];
    }

    function createSupplierCreditLine($data) {
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
            'type' => 'credit',
            'amount' => $grandTotal,
        ];
    }

    function createSupplierDebitLine($data,$type = 'full_paid') {
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
            'type' => 'debit',
            'amount' => $paidAmount,
        ];
    }

    function delete($id) {
        $purchase = $this->repo->find($id);
        if($purchase) {
            return $purchase->delete();
        }

        return false;
    }
}
