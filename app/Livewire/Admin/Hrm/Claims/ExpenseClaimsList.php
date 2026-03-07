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
        $this->confirm('approve', 'question', __('general.pages.hrm.approve_action'), __('general.messages.hrm.confirm_approve_expense_claim'), __('general.pages.hrm.approve_action'));
    }

    public function rejectAlert($id): void
    {
        if (!adminCan('hrm_claims.reject')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('reject', 'warning', __('general.pages.hrm.statuses.rejected'), __('general.messages.hrm.confirm_reject_expense_claim'), __('general.pages.hrm.statuses.rejected'));
    }

    public function approve(): void
    {
        if (!adminCan('hrm_claims.approve')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.expense_claim_not_found'));
            return;
        }

        $this->expenseClaimService->update($this->current->id, [
            'status' => 'approved',
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', __('general.messages.hrm.expense_claim_approved'));
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
            $this->popup('error', __('general.messages.hrm.expense_claim_not_found'));
            return;
        }

        $this->expenseClaimService->update($this->current->id, [
            'status' => 'rejected',
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', __('general.messages.hrm.expense_claim_rejected'));
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
            ->title(__('general.titles.hrm_expense_claims'));
    }
}
