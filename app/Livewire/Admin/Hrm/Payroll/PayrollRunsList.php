<?php

namespace App\Livewire\Admin\Hrm\Payroll;

use App\Enums\PayrollRunStatusEnum;
use App\Services\Hrm\PayrollRunService;
use App\Traits\LivewireOperations;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PayrollRunsList extends Component
{
    use LivewireOperations, WithPagination;

    private PayrollRunService $payrollRunService;

    public array $filters = [];
    public bool $collapseFilters = false;
    public $current;

    public function boot(): void
    {
        $this->payrollRunService = app(PayrollRunService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->payrollRunService->find($id);
    }

    public function deleteAlert($id): void
    {
        if (!adminCan('hrm_payroll.delete')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', __('general.messages.are_you_sure'), __('general.messages.hrm.confirm_delete_payroll_run'), __('general.messages.yes_delete_it'));
    }

    public function delete(): void
    {
        if (!adminCan('hrm_payroll.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.payroll_run_not_found'));
            return;
        }
        $this->payrollRunService->delete($this->current->id);
        $this->popup('success', __('general.messages.hrm.payroll_run_deleted_successfully'));
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function approveAlert($id): void
    {
        if (!adminCan('hrm_payroll.approve')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('approve', 'question', __('general.pages.hrm.approve_action'), __('general.messages.hrm.confirm_approve_payroll_run'), __('general.pages.hrm.approve_action'));
    }

    public function payAlert($id): void
    {
        if (!adminCan('hrm_payroll.pay')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('pay', 'question', __('general.pages.hrm.mark_paid_action'), __('general.messages.hrm.confirm_mark_payroll_paid'), __('general.pages.hrm.mark_paid_action'));
    }

    public function approve(): void
    {
        if (!adminCan('hrm_payroll.approve')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.payroll_run_not_found'));
            return;
        }
        if (($this->current->status?->value ?? $this->current->status) !== PayrollRunStatusEnum::DRAFT->value) {
            $this->popup('warning', __('general.messages.hrm.only_draft_payroll_runs_can_be_approved'));
            $this->dismiss();
            return;
        }

        $this->payrollRunService->update($this->current->id, [
            'status' => PayrollRunStatusEnum::APPROVED->value,
        ]);

        $this->popup('success', __('general.messages.hrm.payroll_run_approved'));
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function pay(): void
    {
        if (!adminCan('hrm_payroll.pay')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.payroll_run_not_found'));
            return;
        }
        if (($this->current->status?->value ?? $this->current->status) !== PayrollRunStatusEnum::APPROVED->value) {
            $this->popup('warning', __('general.messages.hrm.only_approved_payroll_runs_can_be_marked_paid'));
            $this->dismiss();
            return;
        }

        // Accounting transaction posting will be integrated later.
        $this->payrollRunService->update($this->current->id, [
            'status' => PayrollRunStatusEnum::PAID->value,
        ]);

        $this->popup('success', __('general.messages.hrm.payroll_run_marked_paid'));
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    #[On('re-render')]
    public function render()
    {
        $runs = $this->payrollRunService->list([], $this->filters, 10, 'id');

        return layoutView('hrm.payroll.payroll-runs-list', get_defined_vars())
            ->title(__('general.titles.hrm_payroll_runs'));
    }
}
