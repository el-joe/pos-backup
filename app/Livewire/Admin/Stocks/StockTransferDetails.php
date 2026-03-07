<?php

namespace App\Livewire\Admin\Stocks;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\ExpenseService;
use App\Services\PurchaseService;
use App\Services\StockTransferService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class StockTransferDetails extends Component
{
    use LivewireOperations;

    private $stockTransferService, $expenseService, $purchaseService;
    public $id,$stockTransfer;

    #[Url]
    public $activeTab = 'details';

    public $currentItem,$currentExpense;

    function boot() {
        $this->stockTransferService = app(StockTransferService::class);
        $this->expenseService = app(ExpenseService::class);
        $this->purchaseService = app(PurchaseService::class);
    }


    function mount() {
        $this->stockTransfer = $this->stockTransferService->first($this->id);
        if(!$this->stockTransfer) {
            return abort(404);
        }
    }

    function setCurrentItem($itemId) {
        $this->currentItem = $this->stockTransfer->items->where('id',$itemId)->first();
    }

    function setCurrentExpense($expenseId) {
        $this->currentExpense = $this->stockTransfer->expenses()->find($expenseId);

    }

    function deleteExpenseConfirm($id) {
        $this->setCurrentExpense($id);
        AuditLog::log(AuditLogActionEnum::DELETE_EXPENSE_INTO_STOCK_TRANSFER_TRY, ['id' => $id, 'stock_transfer_id' => $this->stockTransfer->id]);
        $this->confirm('deleteExpense','error', __('general.messages.delete_expense_title'), __('general.messages.delete_expense_confirmation'), __('general.messages.do_it') );
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

        $id = $expense->id;

        $this->expenseService->delete($id);

        AuditLog::log(AuditLogActionEnum::DELETE_EXPENSE_INTO_STOCK_TRANSFER, ['id' => $id, 'stock_transfer_id' => $this->stockTransfer->id]);

        $this->mount();

        $this->popup('success', __('general.messages.expense_deleted_successfully'));
    }

    public function render()
    {
        return layoutView('stocks.stock-transfer-details');
    }
}
