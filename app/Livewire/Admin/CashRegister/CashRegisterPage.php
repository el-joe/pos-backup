<?php

namespace App\Livewire\Admin\CashRegister;

use App\Traits\LivewireOperations;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use App\Models\Tenant\CashRegister;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\TransactionService;


class CashRegisterPage extends Component
{
    use LivewireOperations;

    public function render()
    {
        if(session()->has('warning')){
            $this->popup('warning', session('warning'));
            session()->forget('warning');
        }

        $branches = $this->branchService->activeList();

        return layoutView('cash-register.cash-register-page', [
            'aggregates' => $this->aggregates,
            'currentRegister' => $this->currentRegister,
            'branches' => $branches,
        ])->title(__('general.titles.cash-register'));
    }
    public $aggregates = [];
    public $currentRegister;
    private $branchService,$transactionService,$cashRegisterService;

    // Open/close form fields
    public $opening_balance_input;
    public $closing_balance_input;
    public $closing_notes;
    public $branchId;

    function boot() {
        $this->branchService = app(BranchService::class);
        $this->transactionService = app(TransactionService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // find latest open register
        $this->currentRegister = $this->cashRegisterService->getOpenedCashRegister();

        // compute sums for numeric fillable fields
        $row = DB::table('cash_registers')
            ->where('id',$this->currentRegister ? $this->currentRegister->id : 0)
            ->selectRaw(implode(',', [
                'COALESCE(SUM(opening_balance),0) as opening_balance',
                'COALESCE(SUM(total_sales),0) as total_sales',
                'COALESCE(SUM(total_sale_refunds),0) as total_sale_refunds',
                'COALESCE(SUM(total_purchases),0) as total_purchases',
                'COALESCE(SUM(total_purchase_refunds),0) as total_purchase_refunds',
                'COALESCE(SUM(total_expenses),0) as total_expenses',
                'COALESCE(SUM(total_expense_refunds),0) as total_expense_refunds',
                'COALESCE(SUM(total_deposits),0) as total_deposits',
                'COALESCE(SUM(total_withdrawals),0) as total_withdrawals',
                'COALESCE(SUM(closing_balance),0) as closing_balance',
            ]))
            ->first();

        $this->aggregates = (array) $row;
    }

    public function openRegister()
    {
        if(!$this->validator([
            'opening_balance_input' => $this->opening_balance_input,
        ],[
            'opening_balance_input' => 'required|numeric',
        ])) return;

        $cashRegister = CashRegister::create([
            'branch_id' => admin()->branch_id ?? $this->branchId ?? null,
            'admin_id' => admin()->id ?? null,
            'opening_balance' => $this->opening_balance_input,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        $this->transactionService->createOpenBalanceTransaction([
            'branch_id' => admin()->branch_id ?? $this->branchId ?? null,
            'amount' => $this->opening_balance_input,
            'date' => now(),
        ]);

        superAdmins()->map(function($admin) use($cashRegister){
            $admin->notifyCashRegisterOpened($cashRegister);
        });

        $this->opening_balance_input = null;
        $this->loadData();
        $this->alert('success', 'Cash register opened');
    }

    public function closeRegister()
    {
        if(!$this->validator([
            'closing_balance_input' => $this->closing_balance_input,
        ],[
            'closing_balance_input' => 'required|numeric',
        ])) return;

        $reg = $this->cashRegisterService->getOpenedCashRegister();

        if (! $reg) {
            $this->alert('error', 'No open register found.');
            return;
        }

        $reg->update([
            'closing_balance' => $this->closing_balance_input,
            'closed_at' => now(),
            'status' => 'closed',
            'notes' => $this->closing_notes,
        ]);

        $this->transactionService->createOpenBalanceTransaction([
            'branch_id' => admin()->branch_id ?? $this->branchId ?? null,
            'amount' => $this->closing_balance_input,
            'date' => now(),
        ],true);

        superAdmins()->map(function($admin) use($reg){
            $admin->notifyCashRegisterClosed($reg->fresh());
        });


        $this->closing_balance_input = null;
        $this->closing_notes = null;
        $this->loadData();
        $this->alert('success', 'Cash register closed');
    }
}
