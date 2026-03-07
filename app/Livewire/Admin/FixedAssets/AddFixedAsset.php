<?php

namespace App\Livewire\Admin\FixedAssets;

use App\Enums\AuditLogActionEnum;
use App\Enums\AccountTypeEnum;
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
        $this->data['payment_account'] = $this->data['payment_account'] ?? null;
        $this->data['payment_amount'] = $this->data['payment_amount'] ?? 0;

        if (admin()->branch_id) {
            $this->data['branch_id'] = admin()->branch_id;
        }
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
            'data.payment_account' => 'required_if:data.payment_status,partial_paid,full_paid|integer|exists:accounts,id',
            'data.payment_amount' => 'required_if:data.payment_status,partial_paid|numeric|min:0.01|lte:data.cost',
        ]);

        $asset = DB::transaction(function () {
            $branchId = $this->data['branch_id'] ?? (admin()->branch_id ?? null);
            $cost = (float)($this->data['cost'] ?? 0);

            $paymentStatus = (string)($this->data['payment_status'] ?? 'pending');
            $paidAmount = $paymentStatus === 'full_paid'
                ? $cost
                : ($paymentStatus === 'partial_paid' ? (float)($this->data['payment_amount'] ?? 0) : 0.0);

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

                // Optionally record an initial payment (cash/check) to reduce payable
                if ($paidAmount > 0) {
                    $this->fixedAssetService->addPayment($asset->id, [
                        'payment_note' => $this->data['note'] ?? null,
                        'payment_amount' => $paidAmount,
                        'payment_account' => $this->data['payment_account'] ?? null,
                        'payment_date' => $asset->purchase_date ?? now(),
                    ]);

                    $cashRegister = $this->cashRegisterService->getOpenedCashRegister();
                    if ($cashRegister) {
                        $this->cashRegisterService->increment($cashRegister->id, 'total_withdrawals', $paidAmount);
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

        return layoutView('fixed-assets.add-fixed-asset', get_defined_vars())
            ->title(__('general.pages.fixed_assets.new_fixed_asset'));
    }
}
