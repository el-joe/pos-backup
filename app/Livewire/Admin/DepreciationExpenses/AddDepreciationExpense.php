<?php

namespace App\Livewire\Admin\DepreciationExpenses;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\FixedAsset;
use App\Services\BranchService;
use App\Services\ExpenseCategoryService;
use App\Services\ExpenseService;
use App\Services\FixedAssetService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Url;
use Livewire\Component;

class AddDepreciationExpense extends Component
{
    use LivewireOperations;

    private ExpenseService $expenseService;
    private ExpenseCategoryService $expenseCategoryService;
    private BranchService $branchService;
    private FixedAssetService $fixedAssetService;

    public array $data = [];

    #[Url]
    public ?int $fixed_asset_id = null;

    public function boot(): void
    {
        $this->expenseService = app(ExpenseService::class);
        $this->expenseCategoryService = app(ExpenseCategoryService::class);
        $this->branchService = app(BranchService::class);
        $this->fixedAssetService = app(FixedAssetService::class);
    }

    public function mount(): void
    {
        $this->data['expense_date'] = $this->data['expense_date'] ?? now()->format('Y-m-d');
        $this->data['tax_percentage'] = $this->data['tax_percentage'] ?? 0;

        if (admin()->branch_id) {
            $this->data['branch_id'] = admin()->branch_id;
        }

        if ($this->fixed_asset_id) {
            $this->data['fixed_asset_id'] = $this->fixed_asset_id;
        }
    }

    public function saveExpense(): void
    {
        $this->validate([
            'data.branch_id' => 'required|integer|exists:branches,id',
            'data.fixed_asset_id' => 'required|integer|exists:fixed_assets,id',
            'data.expense_category_id' => 'required|integer|exists:expense_categories,id',
            'data.amount' => 'required|numeric|min:0',
            'data.tax_percentage' => 'nullable|numeric|min:0|max:100',
            'data.expense_date' => 'required|date',
            'data.note' => 'nullable|string',
        ]);

        $expense = $this->expenseService->save(null, [
            'branch_id' => $this->data['branch_id'],
            'expense_category_id' => $this->data['expense_category_id'],
            'amount' => $this->data['amount'],
            'tax_percentage' => $this->data['tax_percentage'] ?? 0,
            'expense_date' => $this->data['expense_date'],
            'note' => $this->data['note'] ?? null,
            // 'created_by' => admin()->id ?? null,
            'model_type' => FixedAsset::class,
            'model_id' => $this->data['fixed_asset_id'],
        ]);

        $this->alert('success', __('general.pages.depreciation_expenses.saved'));
        $this->redirect(route('admin.depreciation-expenses.details', $expense->id));
    }

    public function render()
    {
        $branches = $this->branchService->activeList();
        $assets = $this->fixedAssetService->list(relations: [], filter: [], orderByDesc: 'id');

        $expenseCategories = $this->expenseCategoryService->list([], [
            'active' => true,
            'key' => AccountTypeEnum::MAINTENANCE_AND_DEPRECIATION_EXPENSE->value,
        ]);

        return layoutView('depreciation-expenses.add-depreciation-expense', get_defined_vars())
            ->title(__('general.pages.depreciation_expenses.new_depreciation_expense'));
    }
}
