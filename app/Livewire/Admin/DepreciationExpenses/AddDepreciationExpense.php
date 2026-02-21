<?php

namespace App\Livewire\Admin\DepreciationExpenses;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\FixedAsset;
use App\Models\Tenant\FixedAssetExtension;
use App\Services\BranchService;
use App\Services\ExpenseCategoryService;
use App\Services\ExpenseService;
use App\Services\FixedAssetService;
use App\Services\TransactionService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

class AddDepreciationExpense extends Component
{
    use LivewireOperations;

    private ExpenseService $expenseService;
    private ExpenseCategoryService $expenseCategoryService;
    private BranchService $branchService;
    private FixedAssetService $fixedAssetService;
    private TransactionService $transactionService;

    public array $data = [];

    #[Url]
    public ?int $fixed_asset_id = null;

    public function boot(): void
    {
        $this->expenseService = app(ExpenseService::class);
        $this->expenseCategoryService = app(ExpenseCategoryService::class);
        $this->branchService = app(BranchService::class);
        $this->fixedAssetService = app(FixedAssetService::class);
        $this->transactionService = app(TransactionService::class);
    }

    public function mount(): void
    {
        $this->data['expense_date'] = $this->data['expense_date'] ?? now()->format('Y-m-d');
        $this->data['tax_percentage'] = $this->data['tax_percentage'] ?? 0;
        $this->data['fixed_asset_entry_type'] = $this->data['fixed_asset_entry_type'] ?? 'depreciation';
        $this->data['added_useful_life_months'] = $this->data['added_useful_life_months'] ?? 0;

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
            'data.expense_category_id' => 'nullable|integer|exists:expense_categories,id',
            'data.fixed_asset_entry_type' => 'required|in:depreciation,repair_expense,lifespan_extension',
            'data.amount' => 'required|numeric|min:0',
            'data.tax_percentage' => 'nullable|numeric|min:0|max:100',
            'data.expense_date' => 'required|date',
            'data.added_useful_life_months' => 'nullable|integer|min:0',
            'data.note' => 'nullable|string',
        ]);

        $fixedAsset = $this->fixedAssetService->first($this->data['fixed_asset_id']);
        if (!$fixedAsset) {
            $this->alert('error', __('general.pages.depreciation_expenses.fixed_asset_not_found'));
            return;
        }

        if (($this->data['fixed_asset_entry_type'] ?? null) === 'depreciation' && $fixedAsset->is_under_construction) {
            $this->alert('error', __('general.pages.depreciation_expenses.no_depreciation_under_construction'));
            return;
        }

        if (($this->data['fixed_asset_entry_type'] ?? null) !== 'lifespan_extension' && empty($this->data['expense_category_id'])) {
            $this->alert('error', __('general.pages.depreciation_expenses.category_required'));
            return;
        }

        if (($this->data['fixed_asset_entry_type'] ?? null) === 'lifespan_extension') {
            $this->saveLifespanExtension($fixedAsset);
            return;
        }

        $expense = $this->expenseService->save(null, [
            'branch_id' => $this->data['branch_id'],
            'type' => 'normal',
            'expense_category_id' => $this->data['expense_category_id'],
            'amount' => $this->data['amount'],
            'tax_percentage' => $this->data['tax_percentage'] ?? 0,
            'expense_date' => $this->data['expense_date'],
            'note' => $this->data['note'] ?? null,
            // 'created_by' => admin()->id ?? null,
            'model_type' => FixedAsset::class,
            'model_id' => $this->data['fixed_asset_id'],
            'fixed_asset_entry_type' => $this->data['fixed_asset_entry_type'],
        ]);

        $this->alert('success', __('general.pages.depreciation_expenses.saved'));
        $this->redirect(route('admin.depreciation-expenses.details', $expense->id));
    }

    private function saveLifespanExtension(FixedAsset $fixedAsset): void
    {
        $amount = (float) ($this->data['amount'] ?? 0);
        $addedMonths = max(0, (int) ($this->data['added_useful_life_months'] ?? 0));

        if ($amount <= 0) {
            $this->alert('error', __('general.pages.depreciation_expenses.extension_amount_required'));
            return;
        }

        DB::transaction(function () use ($fixedAsset, $amount, $addedMonths) {
            $fixedAsset->update([
                'cost' => (float) $fixedAsset->cost + $amount,
                'useful_life_months' => max(0, (int) $fixedAsset->useful_life_months) + $addedMonths,
            ]);

            FixedAssetExtension::query()->create([
                'fixed_asset_id' => $fixedAsset->id,
                'branch_id' => $this->data['branch_id'],
                'created_by' => admin()->id ?? null,
                'amount' => $amount,
                'added_useful_life_months' => $addedMonths,
                'extension_date' => $this->data['expense_date'],
                'note' => $this->data['note'] ?? null,
            ]);

            $fixedAssetAccount = Account::default('Fixed Asset', AccountTypeEnum::FIXED_ASSET->value, $this->data['branch_id']);
            $branchCashAccount = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $this->data['branch_id']);

            $this->transactionService->create([
                'date' => $this->data['expense_date'] ?? now(),
                'description' => 'Fixed Asset Lifespan Extension for #'.$fixedAsset->code.' - '.$fixedAsset->name,
                'type' => 'fixed_assets',
                'reference_type' => FixedAsset::class,
                'reference_id' => $fixedAsset->id,
                'branch_id' => $this->data['branch_id'],
                'note' => $this->data['note'] ?? '',
                'amount' => $amount,
                'lines' => [
                    [
                        'account_id' => $fixedAssetAccount->id,
                        'type' => 'debit',
                        'amount' => $amount,
                    ],
                    [
                        'account_id' => $branchCashAccount->id,
                        'type' => 'credit',
                        'amount' => $amount,
                    ],
                ],
            ]);
        });

        $this->alert('success', __('general.pages.depreciation_expenses.extension_saved'));
        $this->redirect(route('admin.fixed-assets.details', $fixedAsset->id));
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
            ->title(__('general.pages.depreciation_expenses.new_asset_entry'));
    }
}
