<?php

namespace App\Livewire\Admin\Expenses;

use App\Models\Tenant\ExpenseCategory;
use App\Services\ExpenseCategoryService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ExpenseCategoriesList extends Component
{
    use LivewireOperations, WithPagination;
    private $expenseCategoryService;
    public $current;
    public $data = [];

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
        $expenseCategories = $this->expenseCategoryService->list(perPage : 10 , orderByDesc : 'id');
        return view('livewire.admin.expenses.expense-categories-list',get_defined_vars());
    }
}
