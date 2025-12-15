<?php

namespace App\Livewire\Admin\Purchases;

use App\Enums\AuditLogActionEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\PurchaseService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class PurchasesList extends Component
{

    use LivewireOperations,WithPagination;
    private $purchaseService, $cashRegisterService,$branchService,$userService;
    public $current;

    public $filters = [];
    public $collapseFilters = false;
    public $export = null;

    public $payment = [];

    function boot() {
        $this->purchaseService = app(PurchaseService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
        $this->branchService = app(BranchService::class);
        $this->userService = app(UserService::class);
    }

    function setCurrent($id) {
        $this->current = $this->purchaseService->first($id,[
            'transactions' => fn($q)=> $q->whereIn('type',[
                TransactionTypeEnum::PURCHASE_PAYMENT,
                TransactionTypeEnum::PURCHASE_PAYMENT_REFUND,
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
            $this->cashRegisterService->increment($cashRegister->id, 'total_purchases', $this->payment['amount']);
        }


        $this->purchaseService->addPayment($this->current->id, [
            'payment_note' => $this->payment['note'] ?? null,
            'payment_status' => 'partial_paid',
            'payment_amount' => $this->payment['amount'],
            'branch_id' => $this->current->branch_id,
            'payment_account' => $this->payment['account_id'],
        ]);

        AuditLog::log(AuditLogActionEnum::CREATE_PURCHASE_PAYMENT, ['id' => $this->current->id]);

        $this->alert('success','Payment added successfully!');
        $this->reset('payment');
    }

    public function render()
    {
        if ($this->export == 'excel') {
            $purchases = $this->purchaseService->list(relations: [],filter: $this->filters,orderByDesc: 'id');

            $data = $purchases->map(function ($purchase, $loop) {
                #	Ref No.	Supplier	Branch	Status	Total Amount	Due Amount	Refund Status
                return [
                    'loop' => $loop + 1,
                    'ref_no' => $purchase->ref_no,
                    'supplier' => $purchase->supplier?->name,
                    'branch' => $purchase->branch?->name,
                    'status' => $purchase->status->label(),
                    'total_amount' => $purchase->total_amount,
                    'due_amount' => number_format($purchase->due_amount ?? 0, 2),
                    'refund_status' => $purchase->refund_status->label(),
                ];
            })->toArray();
            $columns = ['loop', 'ref_no', 'supplier', 'branch', 'status', 'total_amount', 'due_amount', 'refund_status'];
            $headers = ['#', 'Ref No.', 'Supplier', 'Branch', 'Status', 'Total Amount', 'Due Amount', 'Refund Status'];
            $fullPath = exportToExcel($data, $columns, $headers, 'purchases');

            AuditLog::log(AuditLogActionEnum::EXPORT_PURCHASES, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }

        $purchases = $this->purchaseService->list(relations: [],filter: $this->filters,perPage: 10,orderByDesc: 'id');
        $branches = $this->branchService->activeList();
        $suppliers = $this->userService->suppliersList();

        return layoutView('purchases.purchases-list', get_defined_vars())
            ->title(__('general.titles.purchases'));
    }
}
