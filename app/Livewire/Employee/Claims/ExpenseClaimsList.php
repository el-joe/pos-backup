<?php

namespace App\Livewire\Employee\Claims;

use App\Models\Tenant\ExpenseClaimCategory;
use App\Services\Hrm\ExpenseClaimService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseClaimsList extends Component
{
    use WithPagination;

    private ExpenseClaimService $expenseClaimService;

    public array $form = [
        'claim_date' => null,
        'category_id' => null,
        'amount' => null,
        'description' => null,
    ];

    public function boot(): void
    {
        $this->expenseClaimService = app(ExpenseClaimService::class);
    }

    public function submit(): void
    {
        $employee = employee();

        $this->validate([
            'form.claim_date' => ['required', 'date'],
            'form.category_id' => ['required', 'exists:expense_claim_categories,id'],
            'form.amount' => ['required', 'numeric', 'min:0.01'],
            'form.description' => ['nullable', 'string', 'max:500'],
        ]);

        $this->expenseClaimService->createWithLines([
            'employee_id' => $employee->id,
            'claim_date' => Carbon::parse($this->form['claim_date'])->toDateString(),
            'status' => 'pending',
            'lines' => [
                [
                    'category_id' => $this->form['category_id'],
                    'amount' => $this->form['amount'],
                    'description' => $this->form['description'],
                ],
            ],
        ]);

        $this->reset('form');
        session()->flash('success', 'Expense claim submitted');
        $this->resetPage();
    }

    public function render()
    {
        $employee = employee();
        $categories = ExpenseClaimCategory::query()->orderBy('name')->get();
        $claims = $this->expenseClaimService->list(['lines.category'], ['employee_id' => $employee->id], 10, 'id');

        return employeeLayoutView('employee.claims.expense-claims-list', get_defined_vars())
            ->title('My Expense Claims');
    }
}
