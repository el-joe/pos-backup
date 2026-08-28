<?php

namespace App\Livewire\Admin\Hrm\Claims;

use App\Enums\ExpenseClaimStatusEnum;
use App\Services\Hrm\EmployeeService;
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
    private EmployeeService $employeeService;

    public array $filters = [
        'status' => 'all',
        'employee_id' => null,
    ];

    public bool $collapseFilters = false;
    public $current;
    public $paymentAccountId = null;

    public function boot(): void
    {
        $this->expenseClaimService = app(ExpenseClaimService::class);
        $this->employeeService = app(EmployeeService::class);
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

        try {
            $this->expenseClaimService->approve($this->current->id, admin()->id);
            $this->popup('success', __('general.messages.hrm.expense_claim_approved'));
        } catch (\Throwable $e) {
            $this->popup('error', $e->getMessage());
        }

        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function payAlert($id): void
    {
        if (!adminCan('hrm_claims.pay')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('pay', 'question', __('general.pages.hrm.pay_action'), __('general.messages.hrm.confirm_pay_expense_claim'), __('general.pages.hrm.pay_action'));
    }

    public function pay(): void
    {
        if (!adminCan('hrm_claims.pay')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.expense_claim_not_found'));
            return;
        }

        try {
            $this->expenseClaimService->pay($this->current->id, $this->paymentAccountId);
            $this->popup('success', __('general.messages.hrm.expense_claim_paid'));
        } catch (\Throwable $e) {
            $this->popup('error', $e->getMessage());
        }

        $this->dismiss();
        $this->reset('current', 'paymentAccountId');
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
            'status' => ExpenseClaimStatusEnum::REJECTED->value,
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
        $employees = $this->employeeService->list([], [], null, 'name');

        return layoutView('hrm.claims.expense-claims-list', get_defined_vars())
            ->title(__('general.titles.hrm_expense_claims'));
    }
}
