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
        $this->asset = $this->fixedAssetService->first($this->id, ['branch', 'createdBy', 'lifespanExtensions', 'orderPayments.account.paymentMethod', 'checks', 'depreciationEntries']);
        if (!$this->asset) {
            abort(404);
        }
    }

    public function render()
    {
        // Legacy: depreciation faked through the Expense module before the depreciation
        // engine existed. Shown for reference only — never posted to going forward.
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

        $depreciationSchedule = $this->asset->depreciationEntries()
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->get();

        return layoutView('fixed-assets.fixed-asset-details', get_defined_vars())
            ->title(__('general.pages.fixed_assets.fixed_asset_details'));
    }
}
