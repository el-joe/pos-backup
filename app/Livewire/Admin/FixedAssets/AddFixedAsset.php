<?php

namespace App\Livewire\Admin\FixedAssets;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Account;
use App\Models\Tenant\FixedAsset;
use App\Services\AccountService;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\FixedAssetService;
use App\Services\TransactionService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AddFixedAsset extends Component
{
    use LivewireOperations;

    private FixedAssetService $fixedAssetService;
    private BranchService $branchService;
    private TransactionService $transactionService;
    private AccountService $accountService;
    private CashRegisterService $cashRegisterService;

    public array $data = [];
    public array $payments = [];

    public function boot(): void
    {
        $this->fixedAssetService = app(FixedAssetService::class);
        $this->branchService = app(BranchService::class);
        $this->transactionService = app(TransactionService::class);
        $this->accountService = app(AccountService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    public function mount(): void
    {
        $this->data['code'] = $this->data['code'] ?? FixedAsset::generateCode();
        $this->data['purchase_date'] = $this->data['purchase_date'] ?? now()->format('Y-m-d');
        $this->data['depreciation_start_date'] = $this->data['depreciation_start_date'] ?? now()->format('Y-m-d');
        $this->data['depreciation_method'] = $this->data['depreciation_method'] ?? FixedAsset::METHOD_STRAIGHT_LINE;
        $this->data['depreciation_rate'] = $this->data['depreciation_rate'] ?? null;
        $this->data['useful_life_months'] = $this->data['useful_life_months'] ?? 0;
        $this->data['cost'] = $this->data['cost'] ?? 0;
        $this->data['salvage_value'] = $this->data['salvage_value'] ?? 0;
        $this->data['status'] = $this->data['status'] ?? FixedAsset::STATUS_ACTIVE;

        $this->data['payment_status'] = $this->data['payment_status'] ?? 'full_paid';
        $this->payments = $this->payments ?: [['account_id' => null, 'amount' => 0]];

        if (admin()->branch_id) {
            $this->data['branch_id'] = admin()->branch_id;
        }
    }

    public function updatedDataPaymentStatus($value): void
    {
        if ($value === 'pending') {
            $this->payments = [];
            return;
        }

        if (empty($this->payments)) {
            $this->addPayment();
        }
    }

    public function addPayment(): void
    {
        $this->payments[] = [
            'account_id' => null,
            'amount' => 0,
        ];
    }

    public function removePayment($index): void
    {
        unset($this->payments[$index]);
        $this->payments = array_values($this->payments);

        if (($this->data['payment_status'] ?? 'pending') !== 'pending' && empty($this->payments)) {
            $this->addPayment();
        }
    }

    protected function paymentSummary(): array
    {
        $totalPaid = collect($this->payments ?? [])->sum(fn($payment) => (float)($payment['amount'] ?? 0));
        $cost = (float)($this->data['cost'] ?? 0);

        return [
            'totalPaid' => $totalPaid,
            'remainingDue' => max(0, $cost - $totalPaid),
            'cost' => $cost,
        ];
    }

    protected function validatePayments(float $cost): array|false
    {
        $paymentStatus = (string)($this->data['payment_status'] ?? 'pending');
        $payments = array_values($this->payments ?? []);

        if ($paymentStatus === 'pending') {
            return [];
        }

        if (empty($payments)) {
            $this->alert('error', __('general.pages.fixed_assets.add_first_payment'));
            return false;
        }

        foreach ($payments as $payment) {
            if (empty($payment['account_id'])) {
                $this->alert('error', __('general.messages.please_select_payment_method_for_all_payments'));
                return false;
            }

            if ((float)($payment['amount'] ?? 0) <= 0) {
                $this->alert('error', __('general.messages.please_enter_valid_amount_for_all_payments'));
                return false;
            }
        }

        $totalPaid = collect($payments)->sum(fn($payment) => (float)($payment['amount'] ?? 0));

        if ($totalPaid > $cost) {
            $this->alert('error', __('general.pages.fixed_assets.payments_cannot_exceed_cost'));
            return false;
        }

        if ($paymentStatus === 'full_paid' && abs($totalPaid - $cost) > 0.01) {
            $this->alert('error', __('general.pages.fixed_assets.full_payment_must_equal_cost'));
            return false;
        }

        if ($paymentStatus === 'partial_paid' && ($totalPaid <= 0 || $totalPaid >= $cost)) {
            $this->alert('error', __('general.pages.fixed_assets.partial_payment_must_be_less_than_cost'));
            return false;
        }

        return $payments;
    }

    protected function cashPaidAmount(array $payments): float
    {
        return collect($payments)->sum(function ($payment) {
            $account = Account::with('paymentMethod')->find($payment['account_id'] ?? null);

            return $account?->paymentMethod?->slug === 'cash'
                ? (float)($payment['amount'] ?? 0)
                : 0.0;
        });
    }

    public function saveAsset(): void
    {
        $this->validate([
            'data.branch_id' => 'nullable|integer',
            'data.code' => 'required|string|max:255',
            'data.name' => 'required|string|max:255',
            'data.purchase_date' => 'nullable|date',
            'data.cost' => 'required|numeric|min:0',
            'data.salvage_value' => 'nullable|numeric|min:0',
            'data.useful_life_months' => 'nullable|integer|min:0',
            'data.depreciation_rate' => 'nullable|numeric|min:0|max:100',
            'data.depreciation_method' => 'required|in:straight_line,declining_balance,double_declining_balance',
            'data.depreciation_start_date' => 'nullable|date',
            'data.status' => 'required|in:active,under_construction,disposed,sold',
            'data.note' => 'nullable|string',
            'data.payment_status' => 'required|in:pending,partial_paid,full_paid',
        ]);

        $cost = (float)($this->data['cost'] ?? 0);
        $payments = $this->validatePayments($cost);
        if ($payments === false) {
            return;
        }

        $asset = DB::transaction(function () use ($payments, $cost) {
            $branchId = $this->data['branch_id'] ?? (admin()->branch_id ?? null);

            $asset = $this->fixedAssetService->save(null, [
                'created_by' => admin()->id ?? null,
                'branch_id' => $branchId,
                'code' => $this->data['code'],
                'name' => $this->data['name'],
                'purchase_date' => $this->data['purchase_date'] ?? null,
                'cost' => $cost,
                'paid_amount' => 0,
                'salvage_value' => $this->data['salvage_value'] ?? 0,
                'useful_life_months' => $this->data['useful_life_months'] ?? 0,
                'depreciation_rate' => $this->data['depreciation_rate'] ?? null,
                'depreciation_method' => $this->data['depreciation_method'] ?? FixedAsset::METHOD_STRAIGHT_LINE,
                'depreciation_start_date' => $this->data['depreciation_start_date'] ?? null,
                'status' => $this->data['status'] ?? FixedAsset::STATUS_ACTIVE,
                'note' => $this->data['note'] ?? null,
            ]);

            if ($cost > 0) {
                // Always record the asset invoice (debit fixed asset, credit payable)
                $this->fixedAssetService->createPurchaseInvoice($asset, $cost, $this->data['note'] ?? '');

                foreach ($payments as $payment) {
                    $this->fixedAssetService->addPayment($asset->id, [
                        'payment_note' => $this->data['note'] ?? null,
                        'payment_amount' => (float)($payment['amount'] ?? 0),
                        'payment_account' => $payment['account_id'] ?? null,
                        'payment_date' => $asset->purchase_date ?? now(),
                    ]);
                }

                $cashPaidAmount = $this->cashPaidAmount($payments);
                if ($cashPaidAmount > 0) {
                    $cashRegister = $this->cashRegisterService->getOpenedCashRegister();
                    if ($cashRegister) {
                        $this->cashRegisterService->increment($cashRegister->id, 'total_withdrawals', $cashPaidAmount);
                    }
                }
            }

            return $asset;
        });

        AuditLog::log(AuditLogActionEnum::FIXED_ASSET_CREATED, [
            'id' => $asset->id,
            'code' => $asset->code,
            'route' => route('admin.fixed-assets.details', $asset->id),
        ]);

        $this->alert('success', __('general.pages.fixed_assets.asset_saved'));
        $this->redirect(route('admin.fixed-assets.details', $asset->id));
    }

    public function render()
    {
        $branches = $this->branchService->activeList();
        $branchId = $this->data['branch_id'] ?? (admin()->branch_id ?? null);
        $paymentAccounts = $this->accountService->getBranchPaymentAccounts($branchId);
        extract($this->paymentSummary());

        return layoutView('fixed-assets.add-fixed-asset', get_defined_vars())
            ->title(__('general.pages.fixed_assets.new_fixed_asset'));
    }
}
