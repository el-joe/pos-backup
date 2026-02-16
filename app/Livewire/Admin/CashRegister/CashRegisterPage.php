<?php

namespace App\Livewire\Admin\CashRegister;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
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

    // Deposit / Withdrawal fields
    public $deposit_amount_input;
    public $deposit_notes;
    public $withdrawal_amount_input;
    public $withdrawal_notes;

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

        superAdmins()->each(function(\App\Models\Tenant\Admin $admin) use($cashRegister){
            $admin->notifyCashRegisterOpened($cashRegister);
        });

        AuditLog::log(AuditLogActionEnum::CASH_REGISTER_OPENED, ['id' => $cashRegister->id]);

        $this->opening_balance_input = null;
        $this->loadData();
        $this->alert('success', __('general.messages.cash_register_opened'));
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
            $this->alert('error', __('general.messages.no_open_register_found'));
            return;
        }

        try{
            DB::beginTransaction();
            $reg->update([
                'closing_balance' => $this->closing_balance_input,
                'closed_at' => now(),
                'status' => 'closed',
                'notes' => $this->closing_notes,
            ]);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->alert('error', __('general.messages.error_closing_cash_register', ['message' => $e->getMessage()]));
            return;
        }

        $this->transactionService->createOpenBalanceTransaction([
            'branch_id' => admin()->branch_id ?? $this->branchId ?? null,
            'amount' => $this->closing_balance_input,
            'date' => now(),
        ],true);

        superAdmins()->each(function(\App\Models\Tenant\Admin $admin) use($reg){
            $admin->notifyCashRegisterClosed($reg->fresh());
        });

        AuditLog::log(AuditLogActionEnum::CASH_REGISTER_CLOSED, ['id' => $reg->id]);

        $this->closing_balance_input = null;
        $this->closing_notes = null;
        $this->loadData();
        $this->alert('success', __('general.messages.cash_register_closed'));
    }

    public function depositCash()
    {
        if(!$this->validator([
            'deposit_amount_input' => $this->deposit_amount_input,
        ],[
            'deposit_amount_input' => 'required|numeric|min:0.01',
        ])) return;

        $reg = $this->cashRegisterService->getOpenedCashRegister();
        if (! $reg) {
            $this->alert('error', __('general.messages.no_open_register_found'));
            return;
        }

        try {
            DB::beginTransaction();
            $this->cashRegisterService->increment($reg->id, 'total_deposits', (float)$this->deposit_amount_input);
            $this->transactionService->createCashDepositTransaction([
                'branch_id' => $reg->branch_id,
                'amount' => (float)$this->deposit_amount_input,
                'date' => now(),
                'note' => $this->deposit_notes,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('general.messages.error_processing_request', ['message' => $e->getMessage()]));
            return;
        }

        $reg = $reg->fresh(['branch', 'admin']);
        superAdmins()->each(function(\App\Models\Tenant\Admin $admin) use($reg){
            $admin->notifyCashRegisterDeposit($reg, $this->deposit_amount_input, $this->deposit_notes);
        });

        AuditLog::log(AuditLogActionEnum::CASH_REGISTER_DEPOSIT, ['id' => $reg->id, 'amount' => (float)$this->deposit_amount_input]);

        $this->deposit_amount_input = null;
        $this->deposit_notes = null;
        $this->loadData();
        $this->alert('success', __('general.messages.deposit_recorded_successfully'));
    }

    public function withdrawCash()
    {
        if(!$this->validator([
            'withdrawal_amount_input' => $this->withdrawal_amount_input,
        ],[
            'withdrawal_amount_input' => 'required|numeric|min:0.01',
        ])) return;

        $reg = $this->cashRegisterService->getOpenedCashRegister();
        if (! $reg) {
            $this->alert('error', __('general.messages.no_open_register_found'));
            return;
        }

        try {
            DB::beginTransaction();
            $this->cashRegisterService->increment($reg->id, 'total_withdrawals', (float)$this->withdrawal_amount_input);
            $this->transactionService->createCashWithdrawalTransaction([
                'branch_id' => $reg->branch_id,
                'amount' => (float)$this->withdrawal_amount_input,
                'date' => now(),
                'note' => $this->withdrawal_notes,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('general.messages.error_processing_request', ['message' => $e->getMessage()]));
            return;
        }

        $reg = $reg->fresh(['branch', 'admin']);
        superAdmins()->each(function(\App\Models\Tenant\Admin $admin) use($reg){
            $admin->notifyCashRegisterWithdrawal($reg, $this->withdrawal_amount_input, $this->withdrawal_notes);
        });

        AuditLog::log(AuditLogActionEnum::CASH_REGISTER_WITHDRAWAL, ['id' => $reg->id, 'amount' => (float)$this->withdrawal_amount_input]);

        $this->withdrawal_amount_input = null;
        $this->withdrawal_notes = null;
        $this->loadData();
        $this->alert('success', __('general.messages.withdrawal_recorded_successfully'));
    }
}
