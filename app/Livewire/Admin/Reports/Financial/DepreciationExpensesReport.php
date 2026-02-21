<?php

namespace App\Livewire\Admin\Reports\Financial;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Expense;
use App\Models\Tenant\FixedAsset;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DepreciationExpensesReport extends Component
{
    public string $from_date;
    public string $to_date;

    public $branch_id = null;
    public $fixed_asset_id = null;
    public $expense_category_id = null;

    public $report;
    public $byAsset;

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');

        $this->loadReport();
    }

    public function updatedFromDate(): void
    {
        $this->loadReport();
    }

    public function updatedToDate(): void
    {
        $this->loadReport();
    }

    public function updatedBranchId(): void
    {
        $this->loadReport();
    }

    public function updatedFixedAssetId(): void
    {
        $this->loadReport();
    }

    public function updatedExpenseCategoryId(): void
    {
        $this->loadReport();
    }

    public function resetFilters(): void
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->branch_id = null;
        $this->fixed_asset_id = null;
        $this->expense_category_id = null;

        $this->loadReport();
    }

    public function loadReport(): void
    {
        $query = Expense::query()
            ->with(['branch', 'category', 'model'])
            ->where('model_type', FixedAsset::class)
            ->where(function ($q) {
                $q->where('fixed_asset_entry_type', 'depreciation')
                    ->orWhereNull('fixed_asset_entry_type');
            });

        if ($this->from_date) {
            $query->whereDate('expense_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $query->whereDate('expense_date', '<=', $this->to_date);
        }

        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }

        if ($this->fixed_asset_id) {
            $query->where('model_id', $this->fixed_asset_id);
        }

        if ($this->expense_category_id) {
            $query->where('expense_category_id', $this->expense_category_id);
        }

        $this->report = $query->orderByDesc('id')->get();

        $summaryQuery = Expense::query()
            ->select('model_id', DB::raw('COUNT(*) as expenses_count'), DB::raw('SUM(amount) as total_amount'))
            ->where('model_type', FixedAsset::class)
            ->where(function ($q) {
                $q->where('fixed_asset_entry_type', 'depreciation')
                    ->orWhereNull('fixed_asset_entry_type');
            });

        if ($this->from_date) {
            $summaryQuery->whereDate('expense_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $summaryQuery->whereDate('expense_date', '<=', $this->to_date);
        }

        if ($this->branch_id) {
            $summaryQuery->where('branch_id', $this->branch_id);
        }

        if ($this->fixed_asset_id) {
            $summaryQuery->where('model_id', $this->fixed_asset_id);
        }

        if ($this->expense_category_id) {
            $summaryQuery->where('expense_category_id', $this->expense_category_id);
        }

        $summary = $summaryQuery
            ->groupBy('model_id')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        $assetIds = $summary->pluck('model_id')->filter()->values();
        $assets = FixedAsset::query()->whereIn('id', $assetIds)->get()->keyBy('id');

        $this->byAsset = $summary->map(function ($row) use ($assets) {
            $asset = $assets[$row->model_id] ?? null;
            return [
                'asset_id' => $row->model_id,
                'asset_name' => $asset ? ($asset->code . ' - ' . $asset->name) : (string) $row->model_id,
                'expenses_count' => (int) $row->expenses_count,
                'total_amount' => (float) $row->total_amount,
            ];
        })->toArray();
    }

    public function render()
    {
        $branches = app(\App\Services\BranchService::class)->activeList();
        $assets = app(\App\Services\FixedAssetService::class)->list(relations: [], filter: [], orderByDesc: 'id');
        $expenseCategories = app(\App\Services\ExpenseCategoryService::class)->list([], [
            'active' => true,
            'key' => AccountTypeEnum::MAINTENANCE_AND_DEPRECIATION_EXPENSE->value,
        ]);

        return layoutView('reports.financial.depreciation-expenses-report', [
            'report' => $this->report,
            'byAsset' => $this->byAsset,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'branch_id' => $this->branch_id,
            'fixed_asset_id' => $this->fixed_asset_id,
            'expense_category_id' => $this->expense_category_id,
            'branches' => $branches,
            'assets' => $assets,
            'expenseCategories' => $expenseCategories,
        ]);
    }
}
