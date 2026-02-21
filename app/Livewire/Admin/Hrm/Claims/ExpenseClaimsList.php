<?php

namespace App\Livewire\Admin\Hrm\Claims;

use App\Services\Hrm\ExpenseClaimService;
use App\Traits\LivewireOperations;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseClaimsList extends Component
{
    use LivewireOperations, WithPagination;

    private ExpenseClaimService $expenseClaimService;

    public array $filters = [
        'status' => 'all',
        'employee_id' => null,
    ];

    public bool $collapseFilters = false;
    public $current;

    public function boot(): void
    {
        $this->expenseClaimService = app(ExpenseClaimService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->expenseClaimService->find($id, ['employee', 'lines.category']);
    }

    public function approveAlert($id): void
    {
        if (!adminCan('hrm_claims.approve')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('approve', 'question', 'Approve?', 'You want to approve this expense claim', 'Yes, approve');
    }

    public function rejectAlert($id): void
    {
        if (!adminCan('hrm_claims.reject')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('reject', 'warning', 'Reject?', 'You want to reject this expense claim', 'Yes, reject');
    }

    public function approve(): void
    {
        if (!adminCan('hrm_claims.approve')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Expense claim not found');
            return;
        }

        $this->expenseClaimService->update($this->current->id, [
            'status' => 'approved',
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', 'Expense claim approved');
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function reject(): void
    {
        if (!adminCan('hrm_claims.reject')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Expense claim not found');
            return;
        }

        $this->expenseClaimService->update($this->current->id, [
            'status' => 'rejected',
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', 'Expense claim rejected');
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'status' => 'all',
            'employee_id' => null,
        ];
        $this->resetPage();
    }

    #[On('re-render')]
    public function render()
    {
        $claims = $this->expenseClaimService->list(['employee'], $this->filters, 10, 'id');

        return layoutView('hrm.claims.expense-claims-list', get_defined_vars())
            ->title('HRM Expense Claims');
    }
}
