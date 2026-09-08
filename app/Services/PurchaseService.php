<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\CheckDirectionEnum;
use App\Enums\CheckStatusEnum;
use App\Enums\PurchaseStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Helpers\PurchaseHelper;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Expense;
use App\Models\Tenant\OrderPayment;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\Check;
use App\Models\Tenant\User;
use App\Repositories\PurchaseRepository;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function __construct(private PurchaseRepository $repo,private ExpenseCategoryService $expenseCategoryService, private StockService $stockService,private TransactionService $transactionService,private AccountService $accountService) {}

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

    /**
     * Shared by save() and receiveDeferredInventory() so the two posting paths cannot drift.
     * Splits each line's tax-free net cost from its share of the order-level discount (and,
     * when the tenant capitalises purchase expenses, its share of the expenses total). The
     * result's 'unit_cost_final' is what belongs in inventory/COGS — never sub_total, which is
     * tax-inclusive and belongs nowhere near a stock valuation.
     */
    private function costPurchaseLines(array $orderProducts, string $discountType, float $discountValue, string $discountClassification, float $expensesTotal, bool $capitaliseExpenses): array
    {
        $lines = [];
        $goodsTotal = 0.0;

        foreach ($orderProducts as $item) {
            $purchasePrice = (float)($item['purchase_price'] ?? 0);
            $discountPct = (float)($item['discount_percentage'] ?? 0);
            $qty = (float)($item['qty'] ?? 0);
            $unitCostNet = $purchasePrice - ($purchasePrice * $discountPct / 100);
            $lineGoodsValue = $unitCostNet * $qty;
            $goodsTotal += $lineGoodsValue;

            $lines[] = $item + [
                'unit_cost_net' => $unitCostNet,
                'line_goods_value' => $lineGoodsValue,
            ];
        }

        // Discount base is goods only — expenses never inflate the discountable amount.
        $orderDiscount = PurchaseHelper::calcDiscount($goodsTotal, $discountType, $discountValue);
        $applyDiscountToCost = $discountClassification !== 'settlement';

        foreach ($lines as &$line) {
            $qty = (float)($line['qty'] ?? 0);
            $share = $goodsTotal > 0 ? $line['line_goods_value'] / $goodsTotal : 0.0;
            $lineDiscount = $applyDiscountToCost ? $orderDiscount * $share : 0.0;
            $lineExpense = $capitaliseExpenses ? $expensesTotal * $share : 0.0;
            $netAmount = $line['line_goods_value'] - $lineDiscount + $lineExpense;
            $line['unit_cost_final'] = $qty > 0 ? $netAmount / $qty : 0.0;
        }
        unset($line);

        return $lines;
    }


    /**
     * Editing a posted purchase is intentionally NOT supported, for the same reason as
     * SellService::save(): re-posting from a new item set without reversing the original
     * purchase_invoice/payment transactions and returning the old items' stock would double-post
     * inventory value and stock quantities. No current caller passes $id, so this guard makes
     * that constraint explicit instead of leaving a half-built edit path. To correct a posted
     * purchase, refund the incorrect items via refundPurchaseItem() and create a new purchase.
     */
    function save($id = null,$data) {
        if($id) {
            throw new \RuntimeException('Editing a posted purchase is not supported. Refund the incorrect items and create a new purchase instead.');
        }

        return DB::transaction(function () use ($data) {
        $purchase = new Purchase();

        $isDeferred = (bool)($data['is_deferred'] ?? false);
        $discountType = in_array(($data['discount_type'] ?? null), ['fixed', 'percentage'], true)
            ? $data['discount_type']
            : 'fixed';
        $discountClassification = in_array(($data['discount_classification'] ?? null), ['trade', 'settlement'], true)
            ? $data['discount_classification']
            : 'trade';

        // fill purchase data
        $status = (PurchaseStatusEnum::tryFrom($data['payment_status'] ?? 'pending') ?? PurchaseStatusEnum::PENDING)->value;
        $purchase->fill([
            'supplier_id' => $data['supplier_id'],
            'branch_id' => $data['branch_id'],
            'ref_no' => $data['ref_no'],
            'order_date' => $data['order_date'],
            'discount_type' => $discountType,
            'discount_value' => $data['discount_value'] ?? 0,
            'discount_classification' => $discountClassification,
            'tax_id' => $data['tax_id'] ?? null,
            'tax_percentage' => $data['tax_rate'] ?? $data['tax_percentage'] ?? 0,
            // 'paid_amount' => $status == 'full_paid' ? $data['grand_total'] : ($data['payment_amount'] ?? $data['paid_amount'] ?? 0),
            'paid_amount' => 0,
            'status' => $status,
            'is_deferred' => $isDeferred,
        ])->save();

        $capitaliseExpenses = (bool) tenantSetting('capitalise_purchase_expenses', false);
        $expensesTotal = array_sum(array_column($data['expenses'] ?? [], 'amount'));
        $costedItems = $this->costPurchaseLines(
            $data['orderProducts'],
            $discountType,
            (float)($data['discount_value'] ?? 0),
            $discountClassification,
            (float)$expensesTotal,
            $capitaliseExpenses
        );

        // fill purchase items data
        $purchase->purchaseItems()->delete();
        foreach ($costedItems as $item) {
            $purchase->purchaseItems()->create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['id'],
                'unit_id' => $item['unit_id'],
                'qty' => $item['qty'],
                'purchase_price' => $item['purchase_price'],
                'unit_cost_net' => $item['unit_cost_net'],
                'discount_percentage' => $item['discount_percentage'],
                'tax_percentage' => $item['tax_percentage'],
                'x_margin' => $item['x_margin'],
                'sell_price' => $item['sell_price'],
            ]);
        }

        // fill expenses data
        $defaultExpenseCategory = $this->expenseCategoryService->getDefaultCategory('purchase');
        $resolvedExpenses = [];
        foreach ($data['expenses']??[] as $item) {
            if($item['expense_category_id']??false){
                $cat_id = $item['expense_category_id'];
            }else{
                $cat_id = $defaultExpenseCategory?->id;
                if(!$cat_id){
                    $defaultExpenseCategory = $this->expenseCategoryService->save(null,[
                        'name' => 'purchase',
                        'ar_name' => 'المشتريات',
                        'default' => 1,
                    ]);
                    $cat_id = $defaultExpenseCategory->id;
                }
            }

            $purchase->expenses()->create([
                'branch_id' => $data['branch_id'],
                'model_type' => Purchase::class,
                'model_id' => $purchase->id,
                'expense_category_id' => $cat_id,
                'amount' => $item['amount'],
                'note' => $item['description'],
                'expense_date' => $item['expense_date'],
            ]);

            // The resolved category id (post-default fallback) is what must drive the GL
            // expense line's account, so createExpenseLine() matches the persisted expense.
            $resolvedExpenses[] = $item + ['expense_category_id' => $cat_id];
        }

        if(!$isDeferred){
            // fill stock data — at net-of-tax, discount/expense-adjusted cost, never sub_total
            foreach ($costedItems as $item) {
                $this->stockService->addStock(productId: $item['id'],unitId: $item['unit_id'],qty: $item['qty'],sellPrice: $item['sell_price'],unitCost: $item['unit_cost_final'],branchId: $data['branch_id']);
            }

            // Grouped by type = Purchase Invoice
            $invoiceData = $data;
            $invoiceData['orderProducts'] = $costedItems;
            $invoiceData['expenses'] = $resolvedExpenses;
            $invoiceData['capitalise_expenses'] = $capitaliseExpenses;
            $invoiceData['discount_classification'] = $discountClassification;

            $transactionData = [
                'description' => 'Purchase Payment for #'.$purchase->ref_no,
                'type' => TransactionTypeEnum::PURCHASE_INVOICE->value,
                'reference_type' => Purchase::class,
                'reference_id' => $purchase->id,
                'branch_id' => $purchase->branch_id,
                'note' => $data['payment_note'] ?? '',
                'amount' => $data['grand_total'] ?? 0,
                'lines' => $this->purchaseInvoiceLines($invoiceData,'create')
            ];

            $this->transactionService->create($transactionData);
        }

        if(($data['payment_status'] ?? 'pending') == 'pending'){
            return $purchase;
        }
        // Grouped by type = Payments
        $this->addPayment($purchase->id, $data);

        return $purchase;
        });
    }

    public function receiveDeferredInventory(int $purchaseId): Purchase
    {
        $purchase = $this->repo->find($purchaseId);
        if(!$purchase){
            throw new \RuntimeException('Purchase not found');
        }

        if(!(bool)$purchase->is_deferred){
            throw new \RuntimeException('Purchase is not deferred');
        }

        if($purchase->inventory_received_at){
            return $purchase;
        }

        $purchase->loadMissing(['purchaseItems', 'expenses']);

        $orderProducts = $purchase->purchaseItems->map(function($item){
            $unitCostAfterDiscount = (float)$item->purchase_price - ((float)$item->purchase_price * ((float)($item->discount_percentage ?? 0)) / 100);
            $subTotal = $unitCostAfterDiscount + ($unitCostAfterDiscount * ((float)($item->tax_percentage ?? 0)) / 100);
            $sellPrice = (float)$item->sell_price;
            if($sellPrice <= 0){
                $sellPrice = $subTotal + ($subTotal * ((float)($item->x_margin ?? 0)) / 100);
            }

            return [
                'id' => $item->product_id,
                'unit_id' => $item->unit_id,
                'qty' => $item->actual_qty,
                'purchase_price' => (float)$item->purchase_price,
                'discount_percentage' => (float)($item->discount_percentage ?? 0),
                'tax_percentage' => (float)($item->tax_percentage ?? 0),
                'x_margin' => (float)($item->x_margin ?? 0),
                'sub_total' => $subTotal,
                'sell_price' => $sellPrice,
            ];
        })->values()->toArray();

        $expenses = $purchase->expenses->map(function($e){
            return [
                'description' => $e->note,
                'amount' => (float)$e->amount,
                'expense_date' => $e->expense_date,
                'expense_category_id' => $e->expense_category_id,
            ];
        })->values()->toArray();

        $discountClassification = $purchase->discount_classification ?? 'trade';
        $capitaliseExpenses = (bool) tenantSetting('capitalise_purchase_expenses', false);
        $expensesTotal = array_sum(array_column($expenses, 'amount'));

        $costedItems = $this->costPurchaseLines(
            $orderProducts,
            $purchase->discount_type,
            (float) $purchase->discount_value,
            $discountClassification,
            (float) $expensesTotal,
            $capitaliseExpenses
        );

        // Discount base is goods only (sub_total is tax-inclusive and used only for the
        // order-level tax/grand-total math below, never as the discount base — expenses never
        // belong in a discountable amount either).
        $orderProductsTotal = array_sum(array_map(fn($p) => (float)$p['sub_total'] * (float)$p['qty'], $orderProducts));
        $orderSubTotal = PurchaseHelper::calcSubtotal($orderProductsTotal, $expensesTotal);
        $discountAmount = PurchaseHelper::calcDiscount($orderProductsTotal, $purchase->discount_type, $purchase->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($orderSubTotal, $discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount, $purchase->tax_percentage);
        $grandTotal = PurchaseHelper::calcGrandTotal($totalAfterDiscount, $taxAmount);

        $data = [
            'supplier_id' => $purchase->supplier_id,
            'branch_id' => $purchase->branch_id,
            'ref_no' => $purchase->ref_no,
            'order_date' => $purchase->order_date,
            'discount_type' => $purchase->discount_type,
            'discount_value' => $purchase->discount_value,
            'discount_classification' => $discountClassification,
            'tax_id' => $purchase->tax_id,
            'tax_percentage' => $purchase->tax_percentage,
            'tax_rate' => $purchase->tax_percentage,
            'payment_note' => 'Deferred inventory received for #'.$purchase->ref_no,
            'orderProducts' => $costedItems,
            'expenses' => $expenses,
            'capitalise_expenses' => $capitaliseExpenses,
            'sub_total' => $orderSubTotal,
            'discount_amount' => $discountAmount,
            'total_after_discount' => $totalAfterDiscount,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
        ];

        DB::beginTransaction();
        try{
            foreach ($costedItems as $item) {
                $this->stockService->addStock(
                    productId: $item['id'],
                    unitId: $item['unit_id'],
                    qty: $item['qty'],
                    sellPrice: $item['sell_price'],
                    unitCost: $item['unit_cost_final'],
                    branchId: $purchase->branch_id
                );
            }

            $transactionData = [
                'description' => 'Deferred Purchase Invoice for #'.$purchase->ref_no,
                'type' => TransactionTypeEnum::PURCHASE_INVOICE->value,
                'reference_type' => Purchase::class,
                'reference_id' => $purchase->id,
                'branch_id' => $purchase->branch_id,
                'note' => $data['payment_note'],
                'amount' => $data['grand_total'] ?? 0,
                'lines' => $this->purchaseInvoiceLines($data,'create')
            ];
            $this->transactionService->create($transactionData);

            $purchase->update([
                'inventory_received_at' => now(),
            ]);

            DB::commit();
        }catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }

        return $purchase->refresh();
    }

    function addPayment($purchaseId, $data , $reverse = false) {
        $purchase = $this->repo->find($purchaseId);
        if(!$purchase) return;

        $paymentAccount = Account::assertPaymentCapable($data['payment_account'] ?? null);

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
        $paidDelta = $data['payment_status'] == 'full_paid' ? ($data['grand_total'] ?? 0) : ($data['payment_amount'] ?? 0);
        if(!$reverse){
            $purchase->increment('paid_amount', $paidDelta);
        }else{
            $purchase->decrement('paid_amount', $paidDelta);
        }

        $counterpartyAccount = $this->getSupplierAccount($data['supplier_id'] ?? null);

        $orderPaymentData = [];
        $orderPaymentData['account_id'] = $paymentAccount->id;
        $orderPaymentData['counterparty_account_id'] = $counterpartyAccount->id ?? null;
        $orderPaymentData['amount'] = (float)($data['payment_status'] == 'full_paid' ? ($data['grand_total'] ?? 0) : ($data['payment_amount'] ?? 0));

        $orderPayment = OrderPayment::create([
            'payable_type' => Purchase::class,
            'payable_id' => $purchaseId,
            'refunded' => $reverse ? 1 : 0,
            'note' => $data['payment_note'] ?? '',
            ... $orderPaymentData
        ]);

        // If the payment account's payment method is CHECK, create issued check record
        if(!$reverse){
            $slug = $paymentAccount->paymentMethod?->slug;
            if($slug === 'check') {
                Check::create([
                    'branch_id' => $purchase->branch_id,
                    'direction' => CheckDirectionEnum::ISSUED->value,
                    'status' => CheckStatusEnum::ISSUED->value,
                    'payable_type' => Purchase::class,
                    'payable_id' => $purchase->id,
                    'order_payment_id' => $orderPayment->id,
                    'supplier_id' => $purchase->supplier_id,
                    'amount' => (float)($orderPaymentData['amount'] ?? 0),
                    'check_number' => $data['check_number'] ?? null,
                    'bank_name' => $data['bank_name'] ?? null,
                    'check_date' => $data['check_date'] ?? null,
                    'due_date' => $data['due_date'] ?? null,
                    'note' => $data['payment_note'] ?? null,
                ]);
            }
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

        // Debit Inventory (for goods purchased, already net of tax/trade discount)
        $inventoryLine = $this->createInventoryLine($data , $reverse);

        // Debit Expense, one line per category (skipped entirely when expenses are capitalised)
        $expenseLines = $this->createExpenseLine($data, $reverse);

        // Debit VAT Receivable (input tax you can claim from tax authority) — the only place
        // recoverable input tax is recognised; it must never also be capitalised into inventory.
        $vatReceivableLine = $this->createVatReceivableLine($data, $reverse);

        // Credit Purchase Discount income — only for a settlement (early-payment) discount.
        // A trade discount was already deducted from inventory cost by costPurchaseLines() and
        // must not also appear here, or it would be double counted.
        $purchaseDiscountLine = $this->createPurchaseDiscountLine($data, $reverse);

        // Credit Supplier (record liability to supplier for total amount owed)
        $supplierCreditLine = $this->createSupplierCreditLine($data, $reverse);

        return array_values(array_filter([
            // Purchase entry --------------------------------
            $inventoryLine,         // DR Inventory (record goods in stock)
            ...$expenseLines,       // DR Expense (record additional expenses, by category)
            $vatReceivableLine,     // DR VAT Receivable (input tax asset)
            $purchaseDiscountLine,  // CR Purchase Discount (settlement discounts only)
            $supplierCreditLine,    // CR Supplier (accounts payable)
        ]));
    }

    function purchasePaymentLines($data,$event = 'create' ,$reverse = false) {
        // ------------------------- Payment entry --------------------------------
        // 3 status (pending, partial_paid, full_paid)
        if(($data['payment_status'] ?? 'pending') == 'pending'){
            return [];
        }

        $supplierDebitLine = $this->createSupplierDebitLine($data,$data['payment_status'] ?? 'full_paid', $reverse);

        $paymentAccountId = $data['payment_account'] ?? null;
        $methodSlug = null;
        if($paymentAccountId) {
            $acc = Account::with('paymentMethod')->find($paymentAccountId);
            $methodSlug = $acc?->paymentMethod?->slug;
        }

        if($methodSlug === 'check') {
            // A purchase payment is a check the business ISSUES.
            $issuedChecks = Account::forCheckDirection('issued', $data['branch_id']);
            $paidAmount = ($data['payment_status'] ?? 'full_paid') == 'full_paid'
                ? (float)($data['grand_total'] ?? 0)
                : (float)($data['payment_amount'] ?? 0);

            $issuedChecksLine = [
                'account_id' => $issuedChecks->id,
                'type' => $reverse ? 'debit' : 'credit',
                'amount' => $paidAmount,
            ];

            return [
                $issuedChecksLine,
                $supplierDebitLine,
            ];
        }

        // Default: credit branch cash
        $branchCashLine = $this->createBranchCashLine($data,$data['payment_status'] ?? 'full_paid', $reverse);

        return [
            $branchCashLine,
            $supplierDebitLine,
        ];
    }

    function createInventoryLine($data,$reverse = false) {
        $getInventoryAccount = Account::default('Inventory',AccountTypeEnum::INVENTORY->value,$data['branch_id']);

        if(!isset($data['orderProducts']) || !is_array($data['orderProducts'])) {
            return false;
        }
        // Net-of-tax cost only — 'unit_cost_final' when the caller went through
        // costPurchaseLines()/receiveDeferredInventory(), otherwise 'sub_total' (used by
        // refunds and stock-taking, which already carry a tax-exclusive value there).
        $inventoryValue = array_sum(array_map(function($item) {
            $unitCost = (float)($item['unit_cost_final'] ?? $item['sub_total'] ?? 0);
            return $unitCost * (float)($item['qty']);
        }, $data['orderProducts']));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getInventoryAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $inventoryValue,
        ];
    }

        function createCogsLine($data,$reverse = false) {
            $getInventoryAccount = Account::default('COGS',AccountTypeEnum::COGS->value,$data['branch_id']);

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
        $paidAmount = $data['payment_amount'] ?? $data['grand_total'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getBranchCashAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $paidAmount,
        ];
    }

    /**
     * Reverses one purchase expense: DR Supplier / CR its own Expense account (+ CR VAT
     * Receivable if it carried its own tax), for the expense's own amount only. Expenses are
     * never discounted (the order discount applies to goods only — PurchaseHelper::calcSubtotal()),
     * so no discount proration belongs here; the previous implementation's use of
     * PurchaseHelper::calcDiscount($expense->amount, ...) against a *fixed* order discount could
     * subtract the whole discount from a single expense line and drive it negative.
     */
    function reversePurchaseExpense($id) {
        return DB::transaction(function () use ($id) {
            $expense = Expense::find($id);
            if(!$expense){
                return;
            }

            $purchaseOrder = $expense->model;
            if(!$purchaseOrder){
                $expense->delete();
                return;
            }

            $amount = (float) $expense->amount;
            $taxAmount = (float) ($expense->amount * ($expense->tax_percentage ?? 0) / 100);
            $grandTotal = $amount + $taxAmount;

            $lines = $this->createExpenseLine([
                'branch_id' => $purchaseOrder->branch_id,
                'expenses' => [[
                    'amount' => $amount,
                    'expense_category_id' => $expense->expense_category_id,
                ]],
            ], true);

            if($taxAmount > 0){
                $lines[] = $this->createVatReceivableLine([
                    'branch_id' => $purchaseOrder->branch_id,
                    'tax_amount' => $taxAmount,
                ], true);
            }

            $lines[] = $this->createSupplierCreditLine([
                'branch_id' => $purchaseOrder->branch_id,
                'supplier_id' => $purchaseOrder->supplier_id,
                'grand_total' => $grandTotal,
            ], true);

            $transactionData = [
                'description' => 'Purchase Expense Reversal for #'.$purchaseOrder->ref_no,
                'type' => TransactionTypeEnum::PURCHASE_INVOICE_REFUND->value,
                'reference_type' => Purchase::class,
                'reference_id' => $purchaseOrder->id,
                'branch_id' => $purchaseOrder->branch_id,
                'note' => 'Reversed purchase expense #'. $expense->id,
                'amount' => $grandTotal,
                'lines' => $lines,
            ];

            $this->transactionService->create($transactionData);

            $expense->delete();

            $purchaseOrder->refresh();

            // Removing the expense shrinks total_amount; if that leaves the supplier
            // overpaid, refund the excess (capped at what this reversal is worth).
            $dueAfter = (float) $purchaseOrder->due_amount;
            if($dueAfter < 0){
                $refundAmount = min($grandTotal, abs($dueAfter));
                if($refundAmount > 0){
                    $this->addPayment($purchaseOrder->id, [
                        'grand_total' => $refundAmount,
                        'payment_note' => 'Refund for reversed purchase expense #'. $expense->id,
                        'payment_status' => 'refunded',
                        'payment_amount' => $refundAmount,
                        'branch_id' => $purchaseOrder->branch_id,
                        'supplier_id' => $purchaseOrder->supplier_id,
                        'payment_account' => $this->getOriginalPaymentAccountId($purchaseOrder->id),
                    ], true);
                }
            }

            $purchaseOrder->refresh();

            $purchaseDue = $purchaseOrder->due_amount;
            $total = $purchaseOrder->total_amount;

            if($purchaseDue <= 0){
                $purchaseOrder->update(['status' => PurchaseStatusEnum::FULL_PAID->value]);
            }elseif($purchaseDue > 0 && $purchaseDue < $total){
                $purchaseOrder->update(['status' => PurchaseStatusEnum::PARTIAL_PAID->value]);
            }else{
                $purchaseOrder->update(['status' => PurchaseStatusEnum::PENDING->value]);
            }
        });
    }

    /**
     * Returns one line per expense category (via ExpenseAccountResolver), not a single lump
     * Expense line — so freight can land on its own account instead of masquerading as a
     * generic expense. Returns [] when the tenant capitalises purchase expenses into inventory
     * cost (costPurchaseLines() already folded them in) so they are never posted twice.
     */
    function createExpenseLine($data,$reverse = false) {
        if($data['capitalise_expenses'] ?? false) {
            return [];
        }

        $expenses = $data['expenses'] ?? [];
        if(empty($expenses)) {
            return [];
        }

        $totalsByCategory = [];
        foreach ($expenses as $expense) {
            $categoryId = $expense['expense_category_id'] ?? null;
            $key = $categoryId ?? 'null';
            $totalsByCategory[$key] ??= ['expense_category_id' => $categoryId, 'amount' => 0.0];
            $totalsByCategory[$key]['amount'] += (float)($expense['amount'] ?? 0);
        }

        $lines = [];
        foreach ($totalsByCategory as $group) {
            $account = ExpenseAccountResolver::resolve((int)$data['branch_id'], $group['expense_category_id']);
            $lines[] = [
                'account_id' => $account->id,
                'type' => $reverse ? 'credit' : 'debit',
                'amount' => $group['amount'],
            ];
        }

        return $lines;
    }

    function createVatReceivableLine($data,$reverse = false) {
        $getVatReceivableAccount = Account::default('Vat Receivable',AccountTypeEnum::VAT_RECEIVABLE->value,$data['branch_id']);


        // get tax amount from data
        $taxAmount = $data['tax_amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getVatReceivableAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $taxAmount,
        ];
    }

    /**
     * A 'trade' discount (the default — IAS 2 §11) was already deducted from inventory cost by
     * costPurchaseLines(), so no separate GL line is needed here; posting one too would double
     * count it. Only a 'settlement' (early-payment) discount is genuine finance income and gets
     * its own credit line.
     */
    function createPurchaseDiscountLine($data,$reverse = false) {
        if(($data['discount_classification'] ?? 'trade') !== 'settlement') {
            return null;
        }

        $getPurchaseDiscountAccount = Account::default('Purchase Discount',AccountTypeEnum::PURCHASE_DISCOUNT->value,$data['branch_id']);

        // get discount amount from data
        $discountAmount = $data['discount_amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getPurchaseDiscountAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $discountAmount,
        ];
    }

    /**
     * Absorbs the gap between a purchase refund's WAC-valued Inventory credit and the
     * original-invoice-priced Supplier debit so the reversal transaction still balances.
     * $variance > 0 (WAC value > original price): DR Purchase Price Variance (loss).
     * $variance < 0 (WAC value < original price): CR Purchase Price Variance (gain).
     */
    function createPurchasePriceVarianceLine($branchId, $variance) {
        $account = Account::default('Purchase Price Variance', AccountTypeEnum::PURCHASE_PRICE_VARIANCE->value, $branchId);

        return [
            'account_id' => $account->id,
            'type' => $variance > 0 ? 'debit' : 'credit',
            'amount' => abs($variance),
        ];
    }

    /**
     * Prefer refunding to the account the supplier was actually paid with; only the
     * caller falls back to a generic account when no payment can be identified.
     */
    function getOriginalPaymentAccountId($purchaseId) {
        $purchase = $this->repo->find($purchaseId);

        $originalPaymentAccountId = OrderPayment::where('payable_type', Purchase::class)
            ->where('payable_id', $purchaseId)
            ->where('refunded', 0)
            ->whereNotNull('account_id')
            ->orderByDesc('id')
            ->value('account_id');

        return $originalPaymentAccountId
            ?? Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $purchase?->branch_id)->id;
    }

    function getSupplierAccount($supplierId = null){
        $getSupplierAccount = Account::where('model_type', User::class)
            ->where('model_id', $supplierId)
            ->where('type', AccountTypeEnum::SUPPLIER->value)
            ->orderBy('id')
            ->first();

        if (!$getSupplierAccount) {
            $getSupplierAccount = $this->accountService->createAccountForUser(User::find($supplierId));
        }

        return $getSupplierAccount;
    }

    function createSupplierCreditLine($data,$reverse = false) {
        $getSupplierAccount = $this->getSupplierAccount($data['supplier_id'] ?? null);

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
        $getSupplierAccount = $this->getSupplierAccount($data['supplier_id'] ?? null);

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
        return DB::transaction(function () use ($id, $qty) {
            return $this->doRefundPurchaseItem($id, $qty);
        });
    }

    private function doRefundPurchaseItem($id,$qty) {
        $purchaseItem = PurchaseItem::findOrFail($id);
        $purchaseOrder = $purchaseItem->purchase;

        $refundableQty = (float) $purchaseItem->qty - (float) $purchaseItem->refunded_qty;
        if((float) $qty <= 0 || (float) $qty > $refundableQty + 0.0001){
            throw new \RuntimeException('Refund quantity exceeds the refundable quantity for this item.');
        }
        // Discount base is the net-of-tax goods amount, mirroring the original posting — tax
        // is computed on top of the discounted net amount, never folded into the discount base.
        $refundedGoodsAmount = $purchaseItem->unit_cost_after_discount * $qty;
        $discountAmount = PurchaseHelper::calcDiscount($refundedGoodsAmount, $purchaseOrder->discount_type , $purchaseOrder->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($refundedGoodsAmount, $discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount, $purchaseOrder->tax_percentage ?? 0);
        // -----------------------------------
        $grandTotalFromRefundedQty = PurchaseHelper::calcGrandTotal($totalAfterDiscount,$taxAmount);
        $purchaseDueAmount = $purchaseOrder->due_amount;
        $totalRefunded = $grandTotalFromRefundedQty - $purchaseDueAmount;

        // Remove stock at the true weighted-average cost first — the original purchase price
        // is no longer necessarily what this quantity is worth in inventory today.
        $stock = $this->stockService->removeFromStock(productId: $purchaseItem->product_id,unitId: $purchaseItem->unit_id,qty: $qty,branchId: $purchaseOrder->branch_id);
        $originalValue = round((float) $purchaseItem->unit_amount_after_tax * (float) $qty, 4);
        $weightedAverageValue = $stock ? round((float) $qty * (float) $stock->unit_cost, 4) : $originalValue;
        $weightedAverageUnitPrice = (float) $qty > 0 ? $weightedAverageValue / (float) $qty : (float) $purchaseItem->unit_amount_after_tax;
        $priceVariance = round($weightedAverageValue - $originalValue, 4);

        // reverse purchase invoice type transaction — Inventory is credited at the WAC value
        // actually leaving stock, not the original invoice price.
        $refundInvoiceData = [
            'branch_id' => $purchaseOrder->branch_id,
            'orderProducts' => [
                [
                    'qty' => (float)$qty,
                    'purchase_price' => (float)$purchaseItem->unit_amount_after_tax,
                    'sub_total' => $weightedAverageUnitPrice,
                ]
            ],
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'discount_classification' => $purchaseOrder->discount_classification,
            'supplier_id' => $purchaseOrder->supplier_id,
            'grand_total' => $grandTotalFromRefundedQty

        ];

        $lines = $this->purchaseInvoiceLines($refundInvoiceData,'create',true);

        if(abs($priceVariance) > 0.005){
            $lines[] = $this->createPurchasePriceVarianceLine($purchaseOrder->branch_id, $priceVariance);
        }

        $transactionData = [
            'description' => 'Purchase Refund for #'.$purchaseOrder->ref_no,
            'type' => TransactionTypeEnum::PURCHASE_INVOICE_REFUND->value,
            'reference_type' => Purchase::class,
            'reference_id' => $purchaseOrder->id,
            'branch_id' => $purchaseOrder->branch_id,
            'note' => 'Refunded for purchase item #'. ($purchaseItem->product?->name ?? 'N/A'),
            'amount' => $grandTotalFromRefundedQty ?? 0,
            'lines' => $lines
        ];

        $this->transactionService->create($transactionData);


        // refund purchase payments
        $totalRefunded = $grandTotalFromRefundedQty - $purchaseDueAmount;
        if($totalRefunded <= 0){
        }else{
            $refundPaymentData = [
                'grand_total' => $totalRefunded,
                'payment_note' => 'Refund for purchase item #'. ($purchaseItem->product?->name ?? 'N/A'),
                'payment_status' => 'refunded',
                'payment_amount' => $totalRefunded,
                'branch_id' => $purchaseOrder->branch_id,
                'supplier_id' => $purchaseOrder->supplier_id,
                'payment_account' => $this->getOriginalPaymentAccountId($purchaseOrder->id),
            ];

            $this->addPayment($purchaseOrder->id, $refundPaymentData , true);
        }

        // refund purchase items qty
        $purchaseItem->increment('refunded_qty',$qty);

        // Stock was already removed above (at the true weighted-average cost) so the
        // Purchase Price Variance line could be computed before the transaction posted.

        $cashRegister = app(\App\Services\CashRegisterService::class)->getOpenedCashRegister();
        if ($cashRegister) {
            app(\App\Services\CashRegisterService::class)->increment(
                $cashRegister->id, 'total_purchase_refunds', $grandTotalFromRefundedQty
            );
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

    function delete($id) {
        $purchase = $this->repo->find($id);
        if($purchase) {
            return $purchase->delete();
        }

        return false;
    }
}
