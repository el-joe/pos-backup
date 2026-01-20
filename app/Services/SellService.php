<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\PurchaseStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Helpers\PurchaseHelper;
use App\Helpers\SaleHelper;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Expense;
use App\Models\Tenant\OrderPayment;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\Sale;
use App\Models\Tenant\SaleItem;
use App\Models\Tenant\User;
use App\Repositories\SellRepository;
use Illuminate\Support\Facades\DB;

class SellService
{
    public function __construct(private SellRepository $repo,private StockService $stockService,private TransactionService $transactionService,private DiscountService $discountService,private AccountService $accountService) {}

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

        $isDeferred = (bool)($data['is_deferred'] ?? false);
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
            'paid_amount' => 0,
            'due_date' => $data['due_date'] ?? null,
            'is_deferred' => $isDeferred,
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

        if(!$isDeferred){
            // fill stock data
            foreach ($data['products'] as $item) {
                $this->stockService->removeFromStock(productId: $item['id'],unitId: $item['unit_id'],qty: ($item['qty']??$item['quantity']),branchId: $data['branch_id']);
            }
        }

        // Save history of discount if applied
        if(isset($data['discount_id']) && $data['discount_id']){
            $discount = $this->discountService->find($data['discount_id']);
            if($discount){
                $this->discountService->saveHistory($discount, $sell);
            }
        }

        if(!$isDeferred){
            // Grouped by type = Sale Invoice
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

            $transactionData = [
                'description' => 'Sale Payment Inventory for #'.$sell->invoice_number,
                'type' => TransactionTypeEnum::SALE_INVOICE->value,
                'reference_type' => Sale::class,
                'reference_id' => $sell->id,
                'branch_id' => $sell->branch_id,
                'note' => $data['payment_note'] ?? '',
                'amount' => $data['payment_amount'] ?? 0,
                'lines' => $this->saleInventoryLines($data,'create')
            ];

            $this->transactionService->create($transactionData);
        }


        if(count($data['payments']??[]) > 0){
            // Grouped by type = Payments
            $this->addPayment($sell->id, $data);
        }

        return $sell->refresh();
    }

    public function deliverDeferredInventory(int $saleId): Sale
    {
        /** @var Sale $sale */
        $sale = $this->repo->find($saleId);

        if(!$sale){
            throw new \RuntimeException('Sale not found');
        }

        if(!(bool)$sale->is_deferred){
            throw new \RuntimeException('Sale is not deferred');
        }

        if($sale->inventory_delivered_at){
            return $sale;
        }

        $sale->loadMissing(['saleItems', 'branch.tax']);

        $data = [
            'branch_id' => $sale->branch_id,
            'customer_id' => $sale->customer_id,
            'payment_note' => 'Deferred inventory delivered for #'.$sale->invoice_number,
            'discount_id' => $sale->discount_id,
            'discount_type' => $sale->discount_type,
            'discount_value' => $sale->discount_value,
            'tax_amount' => $sale->tax_amount,
            'discount_amount' => $sale->discount_amount,
            'payment_amount' => $sale->grand_total_amount,
            'products' => $sale->saleItems->map(function($item){
                return [
                    'id' => $item->product_id,
                    'unit_id' => $item->unit_id,
                    'qty' => $item->actual_qty,
                    'quantity' => $item->actual_qty,
                    'unit_cost' => $item->unit_cost,
                    'sell_price' => $item->sell_price,
                    'taxable' => $item->taxable,
                ];
            })->values()->toArray(),
        ];

        DB::beginTransaction();
        try{
            // Post the deferred invoice + inventory entries at delivery time
            $transactionData = [
                'description' => 'Deferred Sale Invoice for #'.$sale->invoice_number,
                'type' => TransactionTypeEnum::SALE_INVOICE->value,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'branch_id' => $sale->branch_id,
                'note' => $data['payment_note'],
                'amount' => $data['payment_amount'] ?? 0,
                'lines' => $this->saleInvoiceLines($data,'create')
            ];
            $this->transactionService->create($transactionData);

            $transactionData = [
                'description' => 'Deferred Sale Inventory for #'.$sale->invoice_number,
                'type' => TransactionTypeEnum::SALE_INVOICE->value,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'branch_id' => $sale->branch_id,
                'note' => $data['payment_note'],
                'amount' => $data['payment_amount'] ?? 0,
                'lines' => $this->saleInventoryLines($data,'create')
            ];
            $this->transactionService->create($transactionData);

            foreach ($data['products'] as $item) {
                $this->stockService->removeFromStock(
                    productId: $item['id'],
                    unitId: $item['unit_id'],
                    qty: ($item['qty'] ?? $item['quantity']),
                    branchId: $sale->branch_id
                );
            }

            $sale->update([
                'inventory_delivered_at' => now(),
            ]);

            DB::commit();
        }catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }

        return $sale->refresh();
    }

    function addPayment($sellId, $data , $reverse = false) {
        $sell = $this->repo->find($sellId);
        if(!$sell) return;

        $amount = $data['paid_amount'] ?? $data['payment_amount'] ?? 0;

        $transactionData = [
            'description' => ($reverse ? 'Refund ' : '').'Sale Payment for #'.$sell->invoice_number,
            'type' => $reverse ? TransactionTypeEnum::SALE_PAYMENT_REFUND->value : TransactionTypeEnum::SALE_PAYMENT->value,
            'reference_type' => Sale::class,
            'reference_id' => $sell->id,
            'branch_id' => $sell->branch_id,
            'note' => $data['payment_note'] ?? '',
            'amount' => $amount,
            'lines' => $this->salePaymentLines($data,'create',$reverse)
        ];

        $this->transactionService->create($transactionData);
        if(!$reverse){
            $sell->increment('paid_amount', $amount);
        }else{
            $sell->decrement('paid_amount',$amount);
        }
        foreach ($data['payments'] as $payment) {
            $orderPaymentData['account_id'] = $payment['account_id'] ?? $payment['payment_account'] ?? null;
            $orderPaymentData['amount'] = $data['grand_total'] ?? $data['payment_amount'] ?? 0;

            OrderPayment::create([
                'payable_type' => Sale::class,
                'payable_id' => $sellId,
                'refunded' => $reverse ? 1 : 0,
                'note' => $data['payment_note'] ?? '',
                'account_id' => $this->getCustomerAccount($data['customer_id'] ?? null, $orderPaymentData['account_id'] ?? null)->id,
                ... $orderPaymentData
            ]);
        }

        return $sell->refresh();
    }

    function saleInvoiceLines($data,$event = 'create',$reverse = false) { // $reverse mean refund
        // -------------------------- Purchase entry --------------------------------

        // Debit Sales
        $salesLine = $this->createSalesLine($data, $reverse);
        // Debit Expense (for additional purchase-related expenses like shipping, handling, etc.)
        // $expenseLine = $this->createExpenseLine($data, $reverse);

        // Debit VAT Receivable (input tax you can claim from tax authority)
        $vatReceivableLine = $this->createVatReceivableLine($data, $reverse);

        // Credit Sale Discount (reduces cost if supplier gave discount)
        $saleDiscountLine = $this->createSaleDiscountLine($data, $reverse);

        // Credit Customer (record liability to customer for total amount owed)
        $customerCreditLine = $this->createCustomerLine($data,'debit', $reverse);


        return [
            // Purchase entry --------------------------------
            $salesLine,         // DR Inventory (record goods in stock)
            // $expenseLine,           // DR Expense (record additional expenses)
            $vatReceivableLine,     // DR VAT Receivable (input tax asset)
            $saleDiscountLine,      // CR Sale Discount (contra expense)
            $customerCreditLine,    // CR Customer (accounts receivable)
        ];
    }

    function createSalesLine($data, $reverse = false) {
        $getSalesAccount = Account::default('Sales', AccountTypeEnum::SALES->value,  $data['branch_id']);
        // get tax amount from data
        $sellPrice = array_sum(array_map(function($item) {
            return (float)($item['qty']??$item['quantity']) * (float)$item['sell_price'];
        }, $data['products']));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getSalesAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $sellPrice,
        ];
    }

    function saleInventoryLines($data,$event = 'create' ,$reverse = false){
        // Credit Inventory (for goods purchased)
        $inventoryLine = $this->createInventoryLine($data , $reverse);
        // TODO : COGS
        $cogsLine = $this->createCogsLine($data , $reverse);

        return [
            // Inventory entry --------------------------------
            $inventoryLine,        // CR Inventory (if payment is made)
            $cogsLine,             // DR COGS (cost of goods sold expense)
        ];
    }

    function salePaymentLines($data,$event = 'create' ,$reverse = false) {
        // ------------------------- Payment entry --------------------------------
        foreach ($data['payments'] as $payment) {
            $payment['branch_id'] = $data['branch_id'];
            $payment['customer_id'] = $data['customer_id'] ?? null;
            $payment['payment_account'] = $payment['account_id'] ?? null;
            $payment['payment_amount'] = $data['grand_total'] ?? $data['payment_amount'] ?? 0;
            $branchCashLine = $this->createBranchCashLine($payment,'partial_paid', $reverse);

            $customerCreditLine = $this->createCustomerLine($payment,'credit', $reverse);
        }

        return [
            // Payment entry --------------------------------
            $branchCashLine,        // CR Branch Cash (if payment is made)
            $customerCreditLine,     // CR Customer (reduce receivable by paid amount)
        ];
    }

    function createInventoryLine($data,$reverse = false) {
        $getInventoryAccount = Account::default('Inventory', AccountTypeEnum::INVENTORY->value,  $data['branch_id']);

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

    function createCogsLine($data,$reverse = false) {
        $getCogsAccount = Account::default('Cogs', AccountTypeEnum::COGS->value,  $data['branch_id']);

        if(!isset($data['products']) || !is_array($data['products'])) {
            return false;
        }
        // get sub total from order products = product qty * unit cost
        $subTotal = array_sum(array_map(function($item) {
            return (float)($item['qty']??$item['quantity']) * (float)$item['unit_cost'];
        }, $data['products']));

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getCogsAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $subTotal,
        ];
    }


    function createBranchCashLine($data,$type = 'full_paid' ,$reverse = false) {
        $getBranchCashAccount = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value,  $data['branch_id']);

        $paidAmount = $data['payment_amount'] ?? $data['amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getBranchCashAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $paidAmount,
        ];
    }

    function createVatReceivableLine($data,$reverse = false) {
        $getVatReceivableAccount = Account::default('Vat Payable', AccountTypeEnum::VAT_PAYABLE->value,  $data['branch_id']);

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
        $getSaleDiscountAccount = Account::default('Sale Discount', AccountTypeEnum::SALES_DISCOUNT->value,  $data['branch_id']);

        // get discount amount from data
        $discountAmount = $data['discount_amount'] ?? 0;

        //`transaction_id`, `account_id`, `type`, `amount`
        return [
            'account_id' => $getSaleDiscountAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $discountAmount,
        ];
    }

    function getCustomerAccount($customerId = null, $paymentAccountId = null) {
        if(!$paymentAccountId){
            $user = User::find($customerId);
            $getCustomerAccount = $user->accounts->first();
            if(!$getCustomerAccount){
                // create default customer account
                $getCustomerAccount = $this->accountService->createAccountForUser($user);
            }
        }else{
            $getCustomerAccount = Account::find($paymentAccountId);
        }
        return $getCustomerAccount;
    }

    function createCustomerLine($data,$type = 'debit',$reverse = false) {

        $getCustomerAccount = $this->getCustomerAccount($data['customer_id']??null, $data['payment_account']??null);
        // get grand total from data
        $grandTotal = $data['payment_amount'] ?? $data['grand_total'] ?? 0;
        //`transaction_id`, `account_id`, `type`, `amount`

        if($reverse && $type == 'debit'){
            $type = 'credit';
        }elseif($reverse && $type == 'credit'){
            $type = 'debit';
        }

        return [
            'account_id' => $getCustomerAccount->id,
            'type' => $type,
            'amount' => $grandTotal,
        ];
    }

    function refundSaleItem($id,$qty) {
        $saleItem = SaleItem::findOrFail($id);
        $saleOrder = $saleItem->sale;
        $product = (clone $saleItem)->toArray();
        $product['qty'] = $qty;
        $discountAmount = SaleHelper::singleDiscountAmount($product,$saleOrder->saleItems, $saleOrder->discount_type, $saleOrder->discount_value, $saleOrder->max_discount_amount ?? 0);
        $taxPercentage = $saleItem->taxable == 1 ? ($saleOrder->tax_percentage ?? 0) : 0;
        $taxAmount = SaleHelper::singleTaxAmount($product,$saleOrder->saleItems, $saleOrder->discount_type, $saleOrder->discount_value,$taxPercentage, $saleOrder->max_discount_amount ?? 0);
        // -----------------------------------
        $grandTotalFromRefundedQty = SaleHelper::singleGrandTotal($product,$saleOrder->saleItems, $saleOrder->discount_type, $saleOrder->discount_value, $taxPercentage, $saleOrder->max_discount_amount ?? 0);
        $dueAmount = $saleOrder->due_amount;
        $totalRefunded = $grandTotalFromRefundedQty - $dueAmount;

        // reverse sale invoice type transaction
        $refundInvoiceData = [
            'branch_id' => $saleOrder->branch_id,
            'products' => [
                [
                    'qty' => (float)$qty,
                    'sell_price' => (float)$saleItem->sell_price,
                    'unit_cost' => (float)$saleItem->unit_cost,
                ]
            ],
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'customer_id' => $saleOrder->customer_id,
            'sell_price' => (float)$saleItem->sell_price * (float)$qty,
            'grand_total' => $grandTotalFromRefundedQty

        ];

        $transactionData = [
            'description' => 'Sale Refund for #'.$saleOrder->invoice_number,
            'type' => TransactionTypeEnum::SALE_INVOICE_REFUND->value,
            'reference_type' => Sale::class,
            'reference_id' => $saleOrder->id,
            'branch_id' => $saleOrder->branch_id,
            'note' => 'Refunded for sale item #'. ($saleItem->product?->name ?? 'N/A'),
            'amount' => $grandTotalFromRefundedQty ?? 0,
            'lines' => $this->saleInvoiceLines($refundInvoiceData,'create',true)
        ];

        $this->transactionService->create($transactionData);

        $transactionData = [
            'description' => 'Sale Refund Inventory for #'.$saleOrder->invoice_number,
            'type' => TransactionTypeEnum::SALE_INVOICE_REFUND->value,
            'reference_type' => Sale::class,
            'reference_id' => $saleOrder->id,
            'branch_id' => $saleOrder->branch_id,
            'note' => 'Refunded for sale item #'. ($saleItem->product?->name ?? 'N/A'),
            'amount' => $grandTotalFromRefundedQty ?? 0,
            'lines' => $this->saleInventoryLines($refundInvoiceData,'create',true)
        ];

        $this->transactionService->create($transactionData);



        // refund sale payments
        if($totalRefunded <= 0){
        }else{
            $refundPaymentData = [
                'grand_total' => $totalRefunded,
                'payment_note' => 'Refund for sale item #'. ($saleItem->product?->name ?? 'N/A'),
                'payment_status' => 'refunded',
                'payment_amount' => $totalRefunded,
                'branch_id' => $saleOrder->branch_id,
                'customer_id' => $saleOrder->customer_id,
                'payment_account' => $this->getCustomerAccount($saleOrder->customer_id)->id
            ];

            $refundPaymentData['payments'] = [
                $refundPaymentData
            ];

            $this->addPayment($saleOrder->id, $refundPaymentData, true);
        }


        // refund sale items qty
        $saleItem->increment('refunded_qty',$qty);

        // Refund Qty from stock
        $this->stockService->addStock(productId: $saleItem->product_id,unitId: $saleItem->unit_id,qty: $qty,branchId: $saleOrder->branch_id);

        $saleOrder->refresh();

        // $saleDue = $saleOrder->due_amount;
        // $total = $saleOrder->grand_total_amount;

        // if($saleDue <= 0){
        //     $saleOrder->update(['status' => SaleStatusEnum::FULL_PAID->value]);
        // }elseif($saleDue > 0 && $saleDue < $total){
        //     $saleOrder->update(['status' => SaleStatusEnum::PARTIAL_PAID->value]);
        // }elseif($saleDue == $total){
        //     $saleOrder->update(['status' => SaleStatusEnum::PENDING->value]);
        // }
        return $saleOrder;
    }


    function delete($id) {
        $purchase = $this->repo->find($id);
        if($purchase) {
            return $purchase->delete();
        }

        return false;
    }

    function salesSummaryReport($from_date, $to_date, $period)
    {
        return $this->repo->salesSummaryReport($from_date, $to_date, $period);
    }
}
