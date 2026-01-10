<?php

namespace App\Livewire\Admin\Expenses;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\ExpenseCategory;
use App\Services\ExpenseCategoryService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseCategoriesList extends Component
{
    use LivewireOperations, WithPagination;
    private $expenseCategoryService;
    public $current;
    public $data = [
        'active' => false
    ];

    public $collapseFilters = false;
    public $filters = [];
    public $export;

    public $rules = [
        'name' => 'required|string|max:255',
        'ar_name' => 'required|string|max:255',
    ];

    function boot() {
        $this->expenseCategoryService = app(ExpenseCategoryService::class);
    }

    function setCurrent($id) {
        $this->current = $this->expenseCategoryService->find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }else{
            $this->reset('data');
        }
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        AuditLog::log(AuditLogActionEnum::DELETE_EXPENSE_CATEGORY_TRY, ['id' => $id]);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this expense category', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Expense category not found');
            return;
        }

        $id = $this->current->id;

        $this->expenseCategoryService->delete($id);

        AuditLog::log(AuditLogActionEnum::DELETE_EXPENSE_CATEGORY, ['id' => $id]);

        $this->popup('success', 'Expense category deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if (!$this->validator()) return;

        if($this->current){
            $action = AuditLogActionEnum::UPDATE_EXPENSE_CATEGORY;
        }else{
            $action = AuditLogActionEnum::CREATE_EXPENSE_CATEGORY;
        }
        try{
            DB::beginTransaction();
            $expenseCat = $this->expenseCategoryService->save($this->current?->id, $this->data + ['default'=>0]);
            DB::commit();
        }catch(\Exception $e) {
            DB::rollBack();
            $this->popup('error','Error occurred while saving expense category: '.$e->getMessage());
            return;
        }

        AuditLog::log($action, ['id' => $expenseCat->id]);

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
                    'ar_name' => $expenseCategory->ar_name,
                    'status' => $expenseCategory->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name','ar_name','status'];
            $headers = ['#', 'Name', 'Arabic Name', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'expense_categories');

            AuditLog::log(AuditLogActionEnum::EXPORT_EXPENSE_CATEGORIES, ['url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }
        $expenseCategories = $this->expenseCategoryService->list(orderByDesc : 'id', filter : $this->filters + ['parent_only' => 1]);

        return layoutView('expenses.expense-categories-list', get_defined_vars())
            ->title(__( 'general.titles.categories'));
    }
}
