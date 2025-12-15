<?php

namespace App\Livewire\Admin\Sales;

use App\Enums\AuditLogActionEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\SellService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class SalesList extends Component
{

    use LivewireOperations,WithPagination;
    private $sellService,$cashRegisterService,$branchService,$userService;
    public $current;

    public $payment = [];

    public $collapseFilters = false;
    public $export = null;

    public $filters = [];

    function boot() {
        $this->sellService = app(SellService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
        $this->branchService = app(BranchService::class);
        $this->userService = app(UserService::class);
    }

    function setCurrent($id) {
        $this->current = $this->sellService->first($id,[
            'transactions' => fn($q)=> $q->whereIn('type',[
                TransactionTypeEnum::SALE_PAYMENT,
                TransactionTypeEnum::SALE_PAYMENT_REFUND,
            ])
        ]);
    }

    function savePayment() {
        $this->validate([
            'payment.account_id' => 'required|exists:accounts,id',
            'payment.amount' => 'required|numeric|min:0.01|max:'.$this->current->due_amount,
            'payment.note' => 'nullable|string|max:255',
        ]);

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $this->cashRegisterService->increment($cashRegister->id, 'total_sales', $this->payment['amount']);
        }

        $this->sellService->addPayment($this->current->id, [
            'payment_note' => $this->payment['note'] ?? null,
            'payment_amount' => $this->payment['amount'],
            'branch_id' => $this->current->branch_id,
            'payment_account' => $this->payment['account_id'],
            'payments' => [
                [
                    'account_id' => $this->payment['account_id'],
                    'amount' => $this->payment['amount'],
                ]
            ]
        ]);

        AuditLog::log(AuditLogActionEnum::CREATE_SALE_ORDER_PAYMENT,  ['id' => $this->current->id]);

        $this->alert('success','Payment added successfully!');
        $this->reset('payment');

        $this->current = $this->current->refresh();
    }

    public function render()
    {
        if ($this->export == 'excel') {
            $sales = $this->sellService->list(filter: $this->filters,orderByDesc: 'id');

            $data = $sales->map(function ($sale, $loop) {
                #	Invoice No.	Customer	Branch	Total Amount	Due Amount	Refund Status
                return [
                    'loop' => $loop + 1,
                    'invoice_number' => $sale->invoice_number,
                    'customer' => $sale->customer?->name,
                    'branch' => $sale->branch?->name,
                    'total_amount' => $sale->grand_total_amount,
                    'due_amount' => $sale->due_amount,
                    'refund_status' => $sale->refund_status->label()
                ];
            })->toArray();
            $columns = ['loop', 'invoice_number', 'customer', 'branch', 'total_amount', 'due_amount', 'refund_status'];
            $headers = ['#', 'Invoice No.', 'Customer', 'Branch', 'Total Amount', 'Due Amount', 'Refund Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'sales');

            AuditLog::log(AuditLogActionEnum::EXPORT_SALES, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }

        $sales = $this->sellService->list(relations: [],filter: $this->filters,perPage: 10,orderByDesc: 'id');

        $branches = $this->branchService->activeList();
        $customers = $this->userService->customersList();

        return layoutView('sales.sales-list',get_defined_vars());
    }
}
