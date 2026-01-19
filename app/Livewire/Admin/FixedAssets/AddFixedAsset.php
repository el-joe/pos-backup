<?php

namespace App\Livewire\Admin\FixedAssets;

use App\Models\Tenant\FixedAsset;
use App\Services\BranchService;
use App\Services\FixedAssetService;
use App\Traits\LivewireOperations;
use Livewire\Component;

class AddFixedAsset extends Component
{
    use LivewireOperations;

    private FixedAssetService $fixedAssetService;
    private BranchService $branchService;

    public array $data = [];

    public function boot(): void
    {
        $this->fixedAssetService = app(FixedAssetService::class);
        $this->branchService = app(BranchService::class);
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

        $asset = $this->fixedAssetService->save(null, [
            'created_by' => admin()->id ?? null,
            'branch_id' => $this->data['branch_id'] ?? null,
            'code' => $this->data['code'],
            'name' => $this->data['name'],
            'purchase_date' => $this->data['purchase_date'] ?? null,
            'cost' => $this->data['cost'] ?? 0,
            'salvage_value' => $this->data['salvage_value'] ?? 0,
            'useful_life_months' => $this->data['useful_life_months'] ?? 0,
            'depreciation_method' => $this->data['depreciation_method'] ?? 'straight_line',
            'depreciation_start_date' => $this->data['depreciation_start_date'] ?? null,
            'status' => $this->data['status'] ?? 'active',
            'note' => $this->data['note'] ?? null,
        ]);

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
