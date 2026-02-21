<?php

namespace App\Livewire\Admin\FixedAssets;

use App\Models\Tenant\FixedAsset;
use App\Services\ExpenseService;
use App\Services\FixedAssetService;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithPagination;

class FixedAssetDetails extends Component
{
    use LivewireOperations, WithPagination;

    public int $id;
    public $asset;

    private FixedAssetService $fixedAssetService;
    private ExpenseService $expenseService;

    public function boot(): void
    {
        $this->fixedAssetService = app(FixedAssetService::class);
        $this->expenseService = app(ExpenseService::class);
    }

    public function mount(): void
    {
        $this->asset = $this->fixedAssetService->first($this->id, ['branch', 'createdBy', 'lifespanExtensions']);
        if (!$this->asset) {
            abort(404);
        }
    }

    public function render()
    {
        $depreciationExpenses = $this->expenseService->list(
            relations: ['category', 'branch'],
            filter: [
                'model_type' => FixedAsset::class,
                'model_id' => $this->asset->id,
                'fixed_asset_entry_type' => 'depreciation',
            ],
            perPage: 10,
            orderByDesc: 'id'
        );

        $lifespanExtensions = $this->asset->lifespanExtensions()->orderByDesc('id')->get();

        return layoutView('fixed-assets.fixed-asset-details', get_defined_vars())
            ->title(__('general.pages.fixed_assets.fixed_asset_details'));
    }
}
