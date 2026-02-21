<?php

namespace App\Livewire\Admin\Purchases;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\PurchaseService;
use App\Services\UserService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;

class DeferredPurchasesList extends Component
{
    use LivewireOperations, WithPagination;

    private PurchaseService $purchaseService;
    private CashRegisterService $cashRegisterService;
    private BranchService $branchService;
    private UserService $userService;

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

    public function receiveInventory(int $purchaseId): void
    {
        try{
            $purchase = $this->purchaseService->receiveDeferredInventory($purchaseId);

            Artisan::call('app:stock-quantity-alert-check', [
                '--branch_id' => $purchase->branch_id,
                '--tenant_id' => tenant('id'),
            ]);

            $this->alert('success', __('general.messages.inventory_received_successfully'));
        }catch(\Throwable $e){
            $this->alert('error', __('general.messages.failed_to_receive_inventory', ['message' => $e->getMessage()]));
        }
    }

    public function render()
    {
        $effectiveFilters = [
            ...$this->filters,
            'is_deferred' => 1,
            'inventory_received' => 0,
        ];

        $purchases = $this->purchaseService->list(relations: [], filter: $effectiveFilters, perPage: 10, orderByDesc: 'id');
        $branches = $this->branchService->activeList();
        $suppliers = $this->userService->suppliersList();

        return layoutView('purchases.deferred-purchases-list', get_defined_vars())
            ->title(__('general.titles.deferred_purchases'));
    }
}
