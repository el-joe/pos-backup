<?php

namespace App\Livewire\Admin\Expenses;

use App\Models\Tenant\ExpenseCategory;
use App\Services\ExpenseCategoryService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseCategoriesList extends Component
{
    use LivewireOperations, WithPagination;
    private $expenseCategoryService;
    public $current;
    public $data = [];

    public $collapseFilters = false;
    public $filters = [];
    public $export;

    public $rules = [
        'name' => 'required|string|max:255',
    ];

    function boot() {
        $this->expenseCategoryService = app(ExpenseCategoryService::class);
    }

    function setCurrent($id) {
        $this->current = $this->expenseCategoryService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this expense category', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Expense category not found');
            return;
        }

        $this->expenseCategoryService->delete($this->current->id);

        $this->popup('success', 'Expense category deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        $this->expenseCategoryService->save($this->current?->id, $this->data);

        $this->popup('success', 'Expense category saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        if ($this->export == 'excel') {
            $expenseCategories = $this->expenseCategoryService->list(filter : $this->filters , orderByDesc : 'id');

            $data = $expenseCategories->map(function ($expenseCategory, $loop) {
                return [
                    'loop' => $loop + 1,
                    'name' => $expenseCategory->name,
                    'status' => $expenseCategory->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name','status'];
            $headers = ['#', 'Name', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'expense_categories');

            $this->redirectToDownload($fullPath);
        }
        $expenseCategories = $this->expenseCategoryService->list(perPage : 10 , orderByDesc : 'id', filter : $this->filters);

        return layoutView('expenses.expense-categories-list', get_defined_vars())
            ->title(__( 'general.titles.categories'));
    }
}
