<?php

namespace App\Livewire\Admin\Hrm\Payroll;

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
        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this payroll run', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!adminCan('hrm_payroll.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Payroll run not found');
            return;
        }
        $this->payrollRunService->delete($this->current->id);
        $this->popup('success', 'Payroll run deleted successfully');
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
        $this->confirm('approve', 'question', 'Approve?', 'You want to approve this payroll run', 'Yes, approve');
    }

    public function payAlert($id): void
    {
        if (!adminCan('hrm_payroll.pay')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('pay', 'question', 'Mark as paid?', 'You want to mark this payroll run as paid', 'Yes, mark paid');
    }

    public function approve(): void
    {
        if (!adminCan('hrm_payroll.approve')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Payroll run not found');
            return;
        }
        if (($this->current->status ?? null) !== 'draft') {
            $this->popup('warning', 'Only draft payroll runs can be approved');
            $this->dismiss();
            return;
        }

        $this->payrollRunService->update($this->current->id, [
            'status' => 'approved',
        ]);

        $this->popup('success', 'Payroll run approved');
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
            $this->popup('error', 'Payroll run not found');
            return;
        }
        if (($this->current->status ?? null) !== 'approved') {
            $this->popup('warning', 'Only approved payroll runs can be marked as paid');
            $this->dismiss();
            return;
        }

        // Accounting transaction posting will be integrated later.
        $this->payrollRunService->update($this->current->id, [
            'status' => 'paid',
        ]);

        $this->popup('success', 'Payroll run marked as paid');
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    #[On('re-render')]
    public function render()
    {
        $runs = $this->payrollRunService->list([], $this->filters, 10, 'id');

        return layoutView('hrm.payroll.payroll-runs-list', get_defined_vars())
            ->title('HRM Payroll Runs');
    }
}
