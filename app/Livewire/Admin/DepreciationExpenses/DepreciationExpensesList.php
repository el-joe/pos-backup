<?php

namespace App\Livewire\Admin\DepreciationExpenses;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\FixedAsset;
use App\Services\BranchService;
use App\Services\ExpenseCategoryService;
use App\Services\ExpenseService;
use App\Services\FixedAssetService;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithPagination;

class DepreciationExpensesList extends Component
{
    use LivewireOperations, WithPagination;

    private ExpenseService $expenseService;
    private ExpenseCategoryService $expenseCategoryService;
    private BranchService $branchService;
    private FixedAssetService $fixedAssetService;

    public bool $collapseFilters = false;
    public ?string $export = null;
    public array $filters = [];

    public function boot(): void
    {
        $this->expenseService = app(ExpenseService::class);
        $this->expenseCategoryService = app(ExpenseCategoryService::class);
        $this->branchService = app(BranchService::class);
        $this->fixedAssetService = app(FixedAssetService::class);
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
    }

    public function render()
    {
        $baseFilter = [
            'model_type' => FixedAsset::class,
            ...$this->filters,
        ];

        if ($this->export === 'excel') {
            $items = $this->expenseService->list(relations: ['category', 'branch', 'model'], filter: $baseFilter, orderByDesc: 'id');

            $data = $items->map(function ($expense, $loop) {
                $assetName = $expense->model?->name ?? $expense->model_id;

                return [
                    'loop' => $loop + 1,
                    'id' => $expense->id,
                    'branch' => $expense->branch?->name,
                    'asset' => $assetName,
                    'category' => $expense->category?->name,
                    'amount' => $expense->amount,
                    'date' => $expense->expense_date,
                    'note' => $expense->note,
                ];
            })->toArray();

            $columns = ['loop', 'id', 'branch', 'asset', 'category', 'amount', 'date', 'note'];
            $headers = ['#', 'ID', 'Branch', 'Fixed Asset', 'Category', 'Amount', 'Date', 'Note'];
            $fullPath = exportToExcel($data, $columns, $headers, 'depreciation-expenses');

            $this->redirectToDownload($fullPath);
        }

        $expenses = $this->expenseService->list(relations: ['category', 'branch', 'model'], filter: $baseFilter, perPage: 10, orderByDesc: 'id');
        $branches = $this->branchService->activeList();
        $assets = $this->fixedAssetService->list(relations: [], filter: [], orderByDesc: 'id');
        $expenseCategories = $this->expenseCategoryService->list([], [
            'active' => true,
            'key' => AccountTypeEnum::MAINTENANCE_AND_DEPRECIATION_EXPENSE->value,
        ]);

        return layoutView('depreciation-expenses.depreciation-expenses-list', get_defined_vars())
            ->title(__('general.titles.depreciation-expenses'));
    }
}
