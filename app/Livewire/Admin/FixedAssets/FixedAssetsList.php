<?php

namespace App\Livewire\Admin\FixedAssets;

use App\Services\BranchService;
use App\Services\FixedAssetService;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithPagination;

class FixedAssetsList extends Component
{
    use LivewireOperations, WithPagination;

    private FixedAssetService $fixedAssetService;
    private BranchService $branchService;

    public bool $collapseFilters = false;
    public ?string $export = null;
    public array $filters = [];

    public function boot(): void
    {
        $this->fixedAssetService = app(FixedAssetService::class);
        $this->branchService = app(BranchService::class);
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

            $this->redirectToDownload($fullPath);
        }

        $assets = $this->fixedAssetService->list(relations: ['branch'], filter: $this->filters, perPage: 10, orderByDesc: 'id');
        $branches = $this->branchService->activeList();

        return layoutView('fixed-assets.fixed-assets-list', get_defined_vars())
            ->title(__('general.titles.fixed-assets'));
    }
}
