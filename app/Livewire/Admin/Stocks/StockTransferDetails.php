<?php

namespace App\Livewire\Admin\Stocks;

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
        $this->confirm('deleteExpense','error','Delete Expense','Are you sure you want to delete this expense? This action cannot be undone.','Do it!' );
    }

    function deleteExpense() {
        $expense = $this->currentExpense;
        if(!$expense) {
            $this->popup('error','Expense not found.');
            return;
        }

        if($expense->refunded) {
            $this->popup('error','Expense already refunded.');
            return;
        }
        $this->expenseService->delete($expense->id);

        $this->mount();

        $this->popup('success','Expense deleted successfully.');
    }

    public function render()
    {
        return layoutView('stocks.stock-transfer-details');
    }
}
