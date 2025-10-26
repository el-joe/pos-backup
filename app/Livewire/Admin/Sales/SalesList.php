<?php

namespace App\Livewire\Admin\Sales;

use App\Enums\TransactionTypeEnum;
use App\Services\CashRegisterService;
use App\Services\SellService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class SalesList extends Component
{

    use LivewireOperations,WithPagination;
    private $sellService,$cashRegisterService;
    public $current;

    public $payment = [];

    function boot() {
        $this->sellService = app(SellService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
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

        $this->alert('success','Payment added successfully!');
        $this->reset('payment');

        $this->current = $this->current->refresh();
    }

    public function render()
    {
        $sales = $this->sellService->list([],[],10,'id');
        return view('livewire.admin.sales.sales-list',get_defined_vars());
    }
}
