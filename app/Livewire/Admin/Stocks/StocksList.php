<?php

namespace App\Livewire\Admin\Stocks;

use App\Models\Tenant\Stock;
use App\Services\BranchService;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithPagination;

class StocksList extends Component
{
    use LivewireOperations, WithPagination;

    public bool $collapseFilters = false;

    public array $filters = [
        'search' => null,
        'branch_id' => 'all',
    ];

    public function mount(): void
    {
        if (admin()->branch_id) {
            $this->filters['branch_id'] = admin()->branch_id;
        }
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'search' => null,
            'branch_id' => admin()->branch_id ?? 'all',
        ];
    }

    public function render()
    {
        $query = Stock::query()->with(['product', 'branch', 'unit']);

        $branchId = $this->filters['branch_id'] ?? null;
        if (!empty($branchId) && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $search = $this->filters['search'] ?? null;
        if (!empty($search)) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', '%'.$search.'%')
                        ->orWhere('sku', 'like', '%'.$search.'%')
                        ->orWhere('code', 'like', '%'.$search.'%');
                });
            });
        }

        $stocks = $query->orderByDesc('id')->paginate(20);

        $branches = app(BranchService::class)->activeList();

        return layoutView('stocks.stocks-list', get_defined_vars())
            ->title(__('general.titles.stocks'));
    }
}
