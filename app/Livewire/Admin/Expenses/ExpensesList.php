<?php

namespace App\Livewire\Admin\Expenses;

use App\Services\ExpenseCategoryService;
use App\Services\ExpenseService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ExpensesList extends Component
{
    use LivewireOperations,WithPagination;
    private $expenseService, $expenseCategoryService;

    public $current;
    public $data = [];

    public $rules = [
        'expense_category_id' => 'required|integer|exists:expense_categories,id',
        'amount' => 'required|numeric',
        'expense_date' => 'required|date',
        'note' => 'nullable|string',
    ];

    function boot() {
        $this->expenseService = app(ExpenseService::class);
        $this->expenseCategoryService = app(ExpenseCategoryService::class);
    }

    function setCurrent($id) {
        $this->current = $this->expenseService->find($id);
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this expense category', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Expense not found');
            return;
        }

        $this->expenseService->delete($this->current->id);

        $this->popup('success', 'Expense deleted successfully');

        $this->dismiss();

        $this->reset('current');
    }

    function save()  {
        if (!$this->validator()) return;

        $this->expenseService->save($this->current?->id, $this->data + [
            'branch_id' => branch()?->id
        ]);

        $this->popup('success', 'Expense saved successfully');

        $this->dismiss();

        $this->reset('data', 'current');
    }

    public function render()
    {
        $expenses = $this->expenseService->list(['category'],[
            'with_trashed' => true
        ],10,'id')->through(function($expense) {
            return [
                'id' => $expense->id,
                'branch' => $expense->branch?->name ?? 'N/A',
                'target' => $expense->model_type ? (new $expense->model_type)->getTable() : 'N/A',
                'category' => $expense->category?->name ?? 'N/A',
                'amount' => $expense->amount,
                'date' => $expense->expense_date,
                'note' => $expense->note ?? 'N/A',
                'created_at' => $expense->created_at,
                'deleted' => $expense->deleted_at != null,
            ];
        });

        $headers = [
            '#' , 'Branch' ,'Target' , 'Category' , 'Amount' , 'Date' , 'Note' , 'Created At' , 'Actions'
        ];
        $columns = [
            'id' => [ 'type' => 'number'],
            'target' => [ 'type' => 'text'],
            'branch' => [ 'type' => 'text'],
            'category' => [ 'type' => 'text'],
            'amount' => [ 'type' => 'decimal'],
            'date' => [ 'type' => 'date'],
            'note' => [ 'type' => 'text'],
            'created_at' => [ 'type' => 'datetime'],
            'actions' => [ 'type' => 'actions' , 'actions' => [
                [
                    'title' => fn($row) => $row['deleted'] ? 'Deleted' : 'Delete',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn btn-danger btn-sm',
                    'wire:click' => fn($row) => "deleteAlert({$row['id']})",
                    'hide' => function($row) {
                        return $row['target'] == 'purchases';
                    },
                    'disabled' => fn($row) => $row['deleted'],
                    'attributes' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'data-original-title' => fn($row) => $row['deleted'] ? 'Deleted' : 'Delete',
                    ],
                ],
            ]],
        ];

        $expenseCategories = $this->expenseCategoryService->list([],['active'=>true]);

        return view('livewire.admin.expenses.expenses-list', get_defined_vars());
    }
}
