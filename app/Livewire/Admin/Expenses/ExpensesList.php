<?php

namespace App\Livewire\Admin\Expenses;

use App\Services\BranchService;
use App\Services\CashRegisterService;
use App\Services\ExpenseCategoryService;
use App\Services\ExpenseService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ExpensesList extends Component
{
    use LivewireOperations,WithPagination;
    private $expenseService, $expenseCategoryService , $branchService, $cashRegisterService;

    public $current;
    public $data = [];

    public $rules = [
        'expense_category_id' => 'required|integer|exists:expense_categories,id',
        'amount' => 'required|numeric',
        'expense_date' => 'required|date',
        'note' => 'nullable|string',
        'tax_percentage' => 'nullable|numeric|min:0|max:100',
        'branch_id' => 'required|integer|exists:branches,id',
    ];

    public $collapseFilters = false;
    public $filters = [];
    public $export;

    function boot() {
        $this->expenseService = app(ExpenseService::class);
        $this->expenseCategoryService = app(ExpenseCategoryService::class);
        $this->branchService = app(BranchService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    function mount(){
        if(admin()->branch_id){
            $this->data['branch_id'] = admin()->branch_id;
        }
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

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $getTotalRefunded = $this->current->total;
            $this->cashRegisterService->increment($cashRegister->id, 'total_expense_refunds', $getTotalRefunded);
        }


        $this->expenseService->delete($this->current->id);

        $this->popup('success', 'Expense deleted successfully');

        $this->dismiss();

        $this->reset('current');
    }

    function save()  {
        if (!$this->validator()) return;

        $expense = $this->expenseService->save($this->current?->id, $this->data);

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();

        if($cashRegister){
            $getTotalRefunded = $expense->total;
            $this->cashRegisterService->increment($cashRegister->id, 'total_expenses', $getTotalRefunded);
        }

        $this->popup('success', 'Expense saved successfully');

        $this->dismiss();

        $this->reset('data', 'current');
    }

    public function render()
    {

        if ($this->export == 'excel') {
            $expenses = $this->expenseService->list(relations: ['category'],filter: [
                'with_trashed' => true,
                ... $this->filters
            ],orderByDesc: 'id');

            $data = $expenses->map(function ($expense, $loop) {
                #	Branch	Target	Category	Amount	Tax Percentage	Total	Date	Note	Created At
                return [
                    'loop' => $loop + 1,
                    'branch' => $expense->branch?->name ?? 'N/A',
                    'target' => $expense->model_type ? (new $expense->model_type)->getTable() : 'N/A',
                    'category' => $expense->category?->name ?? 'N/A',
                    'amount' => $expense->amount,
                    'tax_percentage' => $expense->tax_percentage,
                    'total' => $expense->total,
                    'date' => $expense->expense_date,
                    'note' => $expense->note ?? 'N/A',
                    'created_at' => $expense->created_at,
                ];
            })->toArray();
            $columns = ['loop', 'branch', 'target', 'category', 'amount', 'tax_percentage', 'total', 'date', 'note', 'created_at'];
            $headers = ['#', 'Branch', 'Target', 'Category', 'Amount', 'Tax Percentage', 'Total', 'Date', 'Note', 'Created At'];
            $fullPath = exportToExcel($data, $columns, $headers, 'expenses');

            $this->redirectToDownload($fullPath);
        }

        $expenses = $this->expenseService->list(['category'],[
            'with_trashed' => true,
            ... $this->filters
        ],10,'id')->through(function($expense) {
            return [
                'id' => $expense->id,
                'branch' => $expense->branch?->name ?? 'N/A',
                'target' => $expense->model_type ? (new $expense->model_type)->getTable() : 'N/A',
                'category' => $expense->category?->name ?? 'N/A',
                'amount' => $expense->amount,
                'tax_percentage' => $expense->tax_percentage,
                'total' => $expense->total,
                'date' => $expense->expense_date,
                'note' => $expense->note ?? 'N/A',
                'created_at' => $expense->created_at,
                'deleted' => $expense->deleted_at != null,
            ];
        });

        $headers = [
            '#' , 'Branch' ,'Target' , 'Category' , 'Amount' , 'Tax Percentage' , 'Total' , 'Date' , 'Note' , 'Created At' , 'Actions'
        ];

        $actions = [];
        if(adminCan('expenses.delete')){
            $actions[] = [
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
            ];
        }

        $columns = [
            'id' => [ 'type' => 'number'],
            'branch' => [ 'type' => 'text'],
            'target' => [ 'type' => 'text'],
            'category' => [ 'type' => 'text'],
            'amount' => [ 'type' => 'decimal'],
            'tax_percentage' => [ 'type' => 'decimal'],
            'total' => [ 'type' => 'decimal'],
            'date' => [ 'type' => 'date'],
            'note' => [ 'type' => 'text'],
            'created_at' => [ 'type' => 'datetime'],
            'actions' => [ 'type' => 'actions' , 'actions' => $actions],
        ];

        $expenseCategories = $this->expenseCategoryService->list([],['active'=>true]);

        $branches = $this->branchService->activeList();

        return layoutView('expenses.expenses-list', get_defined_vars())
            ->title(__( 'general.titles.expenses'));
    }
}
