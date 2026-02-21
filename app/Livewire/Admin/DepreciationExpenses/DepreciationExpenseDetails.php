<?php

namespace App\Livewire\Admin\DepreciationExpenses;

use App\Models\Tenant\Expense;
use App\Models\Tenant\FixedAsset;
use App\Traits\LivewireOperations;
use Livewire\Component;

class DepreciationExpenseDetails extends Component
{
    use LivewireOperations;

    public int $id;
    public $expense;

    public function mount(): void
    {
        $this->expense = Expense::with(['category', 'branch', 'model'])->find($this->id);
        if (!$this->expense || $this->expense->model_type !== FixedAsset::class) {
            abort(404);
        }
    }

    public function render()
    {
        return layoutView('depreciation-expenses.depreciation-expense-details', get_defined_vars())
            ->title(__('general.pages.depreciation_expenses.details_title'));
    }
}
