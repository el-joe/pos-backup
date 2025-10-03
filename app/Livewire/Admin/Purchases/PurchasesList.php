<?php

namespace App\Livewire\Admin\Purchases;

use App\Enums\TransactionTypeEnum;
use App\Services\PurchaseService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PurchasesList extends Component
{

    use LivewireOperations,WithPagination;
    private $purchaseService;
    public $current;

    public $payment = [];

    function boot() {
        $this->purchaseService = app(PurchaseService::class);
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

        $this->purchaseService->addPayment($this->current->id, [
            'payment_note' => $this->payment['note'] ?? null,
            'payment_status' => 'partial_paid',
            'payment_amount' => $this->payment['amount'],
            'branch_id' => $this->current->branch_id,
            'payment_account' => $this->payment['account_id'],
        ]);

        $this->alert('success','Payment added successfully!');
        $this->reset('payment');
    }

    public function render()
    {
        $purchases = $this->purchaseService->list([],[],10,'id');
        return view('livewire.admin.purchases.purchases-list',get_defined_vars());
    }
}
