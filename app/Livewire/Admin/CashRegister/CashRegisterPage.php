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

        $recentSessions = $this->cashRegisterService->list(
            [],
            ['admin_id' => admin()->id, 'branch_id' => admin()->branch_id ?? $this->branchId ?? null],
            5,
            'opened_at'
        );

        return layoutView('cash-register.cash-register-page', [
            'aggregates' => $this->aggregates,
            'currentRegister' => $this->currentRegister,
            'branches' => $branches,
            'recentSessions' => $recentSessions,
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

    // Reconciliation / discrepancy override
    public bool $requiresOverride = false;
    public $discrepancyPreview;
    public $override_reason;

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
        $this->requiresOverride = false;
        $this->discrepancyPreview = null;

        if (! $this->currentRegister) {
            $this->aggregates = [];
            return;
        }

        $this->aggregates = $this->currentRegister->only([
            'opening_balance', 'total_sales', 'total_sale_refunds', 'total_purchases',
            'total_purchase_refunds', 'total_expenses', 'total_expense_refunds',
            'total_deposits', 'total_withdrawals', 'closing_balance',
        ]);
        $this->aggregates['calculated_closing_balance'] = $this->currentRegister->calculated_closing_balance;
    }

    public function openRegister()
    {
        if(!$this->validator([
            'opening_balance_input' => $this->opening_balance_input,
        ],[
            'opening_balance_input' => 'required|numeric',
        ])) return;

        try {
            DB::beginTransaction();

            $cashRegister = CashRegister::create([
                'branch_id' => admin()->branch_id ?? $this->branchId ?? null,
                'admin_id' => admin()->id ?? null,
                'opening_balance' => $this->opening_balance_input,
                'status' => 'open',
                'opened_at' => now(),
                'currency_code' => currency()?->code,
                'exchange_rate' => currency()?->conversion_rate ?? 1,
            ]);

            $this->transactionService->createOpenBalanceTransaction([
                'branch_id' => admin()->branch_id ?? $this->branchId ?? null,
                'amount' => $this->opening_balance_input,
                'date' => now(),
            ]);

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->getCode() === '23000') {
                $this->alert('error', __('general.messages.register_already_open'));
                $this->loadData();
                return;
            }
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('general.messages.error_processing_request', ['message' => $e->getMessage()]));
            return;
        }

        superAdmins()->each(function(\App\Models\Tenant\Admin $admin) use($cashRegister){
            $admin->notifyCashRegisterOpened($cashRegister);
        });

        AuditLog::log(AuditLogActionEnum::CASH_REGISTER_OPENED, ['id' => $cashRegister->id]);

        $this->opening_balance_input = null;
        $this->loadData();
        $this->alert('success', __('general.messages.cash_register_opened'));
    }

    public function confirmCloseRegister()
    {
        $this->confirm(
            'closeRegister',
            'warning',
            __('general.pages.cash_register.confirm_close_title'),
            __('general.pages.cash_register.confirm_close_text'),
            __('general.pages.cash_register.close_register')
        );
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

        $expected = $reg->calculated_closing_balance;
        $discrepancy = round((float) $this->closing_balance_input - $expected, 2);
        $threshold = (float) config('cash_register.discrepancy_threshold');

        if (abs($discrepancy) > $threshold && ! $this->requiresOverride) {
            $this->requiresOverride = true;
            $this->discrepancyPreview = $discrepancy;
            return;
        }

        if ($this->requiresOverride && ! $this->override_reason) {
            $this->addError('override_reason', __('general.messages.override_reason_required'));
            return;
        }

        try{
            DB::beginTransaction();
            $reg = $this->cashRegisterService->getOpenedCashRegister([], true);
            if (! $reg) {
                DB::rollBack();
                $this->alert('error', __('general.messages.no_open_register_found'));
                return;
            }

            $reg->update([
                'closing_balance' => $this->closing_balance_input,
                'expected_closing_balance' => $expected,
                'discrepancy' => $discrepancy,
                'closed_at' => now(),
                'status' => 'closed',
                'notes' => $this->closing_notes,
                'discrepancy_reason' => $this->requiresOverride ? $this->override_reason : null,
                'discrepancy_approved_by' => $this->requiresOverride ? admin()->id : null,
                'discrepancy_approved_at' => $this->requiresOverride ? now() : null,
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

        if ($this->requiresOverride) {
            AuditLog::log(AuditLogActionEnum::CASH_REGISTER_DISCREPANCY_OVERRIDDEN, [
                'id' => $reg->id,
                'discrepancy' => $discrepancy,
                'reason' => $this->override_reason,
            ]);
        }

        $this->closing_balance_input = null;
        $this->closing_notes = null;
        $this->requiresOverride = false;
        $this->discrepancyPreview = null;
        $this->override_reason = null;
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
