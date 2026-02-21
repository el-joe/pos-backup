<?php

namespace App\Livewire\Admin\Hrm\Payroll;

use App\Services\Hrm\PayrollRunService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class PayrollRunModal extends Component
{
    use LivewireOperations;

    private PayrollRunService $payrollRunService;

    public $current;
    public array $data = [
        'month' => null,
        'year' => null,
        'status' => 'draft',
        'total_payout' => 0,
    ];

    public function boot(): void
    {
        $this->payrollRunService = app(PayrollRunService::class);
    }

    #[On('hrm-payroll-run-set-current')]
    public function setCurrent($id = null): void
    {
        $this->current = $this->payrollRunService->find($id);
        if ($this->current) {
            $this->data = [
                'month' => $this->current->month,
                'year' => $this->current->year,
                'status' => $this->current->status,
                'total_payout' => $this->current->total_payout,
            ];
        } else {
            $this->reset('current', 'data');
            $this->data = [
                'month' => null,
                'year' => null,
                'status' => 'draft',
                'total_payout' => 0,
            ];
        }
    }

    public function save(): void
    {
        $isUpdate = (bool) $this->current?->id;
        if ($isUpdate && !adminCan('hrm_payroll.update')) {
            abort(403);
        }
        if (!$isUpdate && !adminCan('hrm_payroll.create')) {
            abort(403);
        }

        $this->validate([
            'data.month' => 'required|integer|min:1|max:12',
            'data.year' => 'required|integer|min:2000|max:2100',
            'data.status' => 'required|string|max:50',
            'data.total_payout' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            if ($isUpdate) {
                $this->payrollRunService->update($this->current->id, $this->data);
            } else {
                $this->payrollRunService->create($this->data);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Error while saving payroll run: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Payroll run saved successfully');
        $this->dismiss();
        $this->reset('current', 'data');
        $this->dispatch('re-render');
    }

    public function render()
    {
        return view('livewire.admin.hrm.payroll.payroll-run-modal');
    }
}
