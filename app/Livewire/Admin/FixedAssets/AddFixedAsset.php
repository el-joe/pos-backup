<?php

namespace App\Livewire\Admin\FixedAssets;

use App\Enums\AccountTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\FixedAsset;
use App\Services\BranchService;
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

    public array $data = [];

    public function boot(): void
    {
        $this->fixedAssetService = app(FixedAssetService::class);
        $this->branchService = app(BranchService::class);
        $this->transactionService = app(TransactionService::class);
    }

    public function mount(): void
    {
        $this->data['code'] = $this->data['code'] ?? FixedAsset::generateCode();
        $this->data['purchase_date'] = $this->data['purchase_date'] ?? now()->format('Y-m-d');
        $this->data['depreciation_start_date'] = $this->data['depreciation_start_date'] ?? now()->format('Y-m-d');
        $this->data['depreciation_method'] = $this->data['depreciation_method'] ?? 'straight_line';
        $this->data['useful_life_months'] = $this->data['useful_life_months'] ?? 0;
        $this->data['cost'] = $this->data['cost'] ?? 0;
        $this->data['salvage_value'] = $this->data['salvage_value'] ?? 0;
        $this->data['status'] = $this->data['status'] ?? 'active';

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
            'data.depreciation_method' => 'required|string|max:255',
            'data.depreciation_start_date' => 'nullable|date',
            'data.status' => 'required|string|max:255',
            'data.note' => 'nullable|string',
        ]);

        $asset = DB::transaction(function () {
            $branchId = $this->data['branch_id'] ?? (admin()->branch_id ?? null);
            $cost = (float)($this->data['cost'] ?? 0);

            $asset = $this->fixedAssetService->save(null, [
                'created_by' => admin()->id ?? null,
                'branch_id' => $branchId,
                'code' => $this->data['code'],
                'name' => $this->data['name'],
                'purchase_date' => $this->data['purchase_date'] ?? null,
                'cost' => $cost,
                'salvage_value' => $this->data['salvage_value'] ?? 0,
                'useful_life_months' => $this->data['useful_life_months'] ?? 0,
                'depreciation_method' => $this->data['depreciation_method'] ?? 'straight_line',
                'depreciation_start_date' => $this->data['depreciation_start_date'] ?? null,
                'status' => $this->data['status'] ?? 'active',
                'note' => $this->data['note'] ?? null,
            ]);

            // Save transaction and transaction lines if needed
            // Debit: Fixed Asset account, Credit: Branch Cash account
            if ($cost > 0) {
                $fixedAssetAccount = Account::default('Fixed Asset', AccountTypeEnum::FIXED_ASSET->value, $branchId);
                $branchCashAccount = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $branchId);

                $this->transactionService->create([
                    'date' => $asset->purchase_date ?? now(),
                    'description' => 'Fixed Asset Purchase for #'.$asset->code.' - '.$asset->name,
                    'type' => TransactionTypeEnum::FIXED_ASSETS->value,
                    'reference_type' => FixedAsset::class,
                    'reference_id' => $asset->id,
                    'branch_id' => $branchId,
                    'note' => $this->data['note'] ?? '',
                    'amount' => $cost,
                    'lines' => [
                        [
                            'account_id' => $fixedAssetAccount->id,
                            'type' => 'debit',
                            'amount' => $cost,
                        ],
                        [
                            'account_id' => $branchCashAccount->id,
                            'type' => 'credit',
                            'amount' => $cost,
                        ],
                    ],
                ]);
            }

            return $asset;
        });

        $this->alert('success', __('general.pages.fixed_assets.asset_saved'));
        $this->redirect(route('admin.fixed-assets.details', $asset->id));
    }

    public function render()
    {
        $branches = $this->branchService->activeList();

        return layoutView('fixed-assets.add-fixed-asset', get_defined_vars())
            ->title(__('general.pages.fixed_assets.new_fixed_asset'));
    }
}
