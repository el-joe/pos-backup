<?php

namespace App\Livewire\Admin\FixedAssets;

use App\Enums\AuditLogActionEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Account;
use App\Services\AccountService;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\FixedAssetService;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithPagination;

class FixedAssetsList extends Component
{
    use LivewireOperations, WithPagination;

    private FixedAssetService $fixedAssetService;
    private BranchService $branchService;
    private AccountService $accountService;
    private CashRegisterService $cashRegisterService;

    public $current;
    public array $payment = [];

    public bool $collapseFilters = false;
    public ?string $export = null;
    public array $filters = [];

    public function boot(): void
    {
        $this->fixedAssetService = app(FixedAssetService::class);
        $this->branchService = app(BranchService::class);
        $this->accountService = app(AccountService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->fixedAssetService->first($id, [
            'transactions' => fn($q) => $q->where('type', TransactionTypeEnum::FIXED_ASSETS)->with('lines')->orderByDesc('id'),
            'orderPayments' => fn($q) => $q->with(['account.paymentMethod'])->latest('id'),
            'checks',
        ]);
    }

    public function savePayment(): void
    {
        if (!$this->current) {
            return;
        }

        $this->validate([
            'payment.account_id' => 'required|exists:accounts,id',
            'payment.amount' => 'required|numeric|min:0.01|max:'.$this->current->due_amount,
            'payment.note' => 'nullable|string|max:255',
        ]);

        $this->current = $this->fixedAssetService->addPayment($this->current->id, [
            'payment_note' => $this->payment['note'] ?? null,
            'payment_amount' => $this->payment['amount'],
            'payment_account' => $this->payment['account_id'],
            'payment_date' => now(),
        ]);

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();
        $paymentAccount = Account::with('paymentMethod')->find($this->payment['account_id']);
        if ($cashRegister && $paymentAccount?->paymentMethod?->slug === 'cash') {
            $this->cashRegisterService->increment($cashRegister->id, 'total_withdrawals', (float) $this->payment['amount']);
        }

        AuditLog::log(AuditLogActionEnum::from('fixed_asset_payment_created'), [
            'id' => $this->current->id,
            'amount' => (float) $this->payment['amount'],
            'route' => route('admin.fixed-assets.details', $this->current->id),
        ]);

        $this->alert('success', __('general.messages.payment_added_successfully'));
        $this->reset('payment');

        $this->setCurrent($this->current->id);
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
    }

    public function render()
    {
        if ($this->export === 'excel') {
            $assets = $this->fixedAssetService->list(relations: ['branch'], filter: $this->filters, orderByDesc: 'id');

            $data = $assets->map(function ($asset, $loop) {
                return [
                    'loop' => $loop + 1,
                    'code' => $asset->code,
                    'name' => $asset->name,
                    'branch' => $asset->branch?->name,
                    'status' => $asset->status,
                    'cost' => $asset->cost,
                    'net_book_value' => $asset->net_book_value,
                ];
            })->toArray();

            $columns = ['loop', 'code', 'name', 'branch', 'status', 'cost', 'net_book_value'];
            $headers = ['#', 'Code', 'Name', 'Branch', 'Status', 'Cost', 'Net Book Value'];
            $fullPath = exportToExcel($data, $columns, $headers, 'fixed-assets');

            AuditLog::log(AuditLogActionEnum::from('export_fixed_assets'), ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }

        $assets = $this->fixedAssetService->list(relations: ['branch', 'checks', 'orderPayments.account.paymentMethod'], filter: $this->filters, perPage: 10, orderByDesc: 'id');
        $branches = $this->branchService->activeList();
        $paymentAccounts = $this->accountService->getBranchPaymentAccounts($this->current?->branch_id);

        return layoutView('fixed-assets.fixed-assets-list', get_defined_vars())
            ->title(__('general.titles.fixed-assets'));
    }
}
