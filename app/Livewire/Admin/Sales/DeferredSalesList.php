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
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;

class DeferredSalesList extends Component
{
    use LivewireOperations, WithPagination;

    private SellService $sellService;
    private CashRegisterService $cashRegisterService;
    private BranchService $branchService;
    private UserService $userService;

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
            'customer_id' => $this->current->customer_id,
            'payments' => [
                [
                    'account_id' => $this->payment['account_id'],
                    'amount' => $this->payment['amount'],
                ]
            ]
        ]);

        AuditLog::log(AuditLogActionEnum::CREATE_SALE_ORDER_PAYMENT,  ['id' => $this->current->id]);

        $this->alert('success', __('general.messages.payment_added_successfully'));
        $this->reset('payment');

        $this->current = $this->current->refresh();
    }

    public function deliverInventory(int $saleId): void
    {
        try{
            $sale = $this->sellService->deliverDeferredInventory($saleId);

            Artisan::call('app:stock-quantity-alert-check', [
                '--branch_id' => $sale->branch_id,
                '--tenant_id' => tenant('id'),
            ]);

            $this->alert('success', __('general.messages.inventory_delivered_successfully'));
        }catch(\Throwable $e){
            $this->alert('error', __('general.messages.failed_to_deliver_inventory', ['message' => $e->getMessage()]));
        }
    }

    public function render()
    {
        $effectiveFilters = [
            ...$this->filters,
            'is_deferred' => 1,
            'inventory_delivered' => 0,
        ];

        $sales = $this->sellService->list(filter: $effectiveFilters, perPage: 10, orderByDesc: 'id');

        $branches = $this->branchService->activeList();
        $customers = $this->userService->customersList();

        return layoutView('sales.deferred-sales-list', get_defined_vars())
            ->title(__('general.titles.deferred_sales'));
    }
}
