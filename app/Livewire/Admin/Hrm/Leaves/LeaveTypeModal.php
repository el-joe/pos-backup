<?php

namespace App\Livewire\Admin\Hrm\Leaves;

use App\Services\Hrm\LeaveTypeService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class LeaveTypeModal extends Component
{
    use LivewireOperations;

    private LeaveTypeService $leaveTypeService;

    public $current;
    public array $data = [
        'name' => null,
        'yearly_allowance' => 0,
        'is_paid' => true,
    ];

    public function boot(): void
    {
        $this->leaveTypeService = app(LeaveTypeService::class);
    }

    #[On('hrm-leave-type-set-current')]
    public function setCurrent($id = null): void
    {
        $this->current = $this->leaveTypeService->find($id);
        if ($this->current) {
            $this->data = [
                'name' => $this->current->name,
                'yearly_allowance' => $this->current->yearly_allowance,
                'is_paid' => (bool) $this->current->is_paid,
            ];
        } else {
            $this->reset('current', 'data');
            $this->data = [
                'name' => null,
                'yearly_allowance' => 0,
                'is_paid' => true,
            ];
        }
    }

    public function save(): void
    {
        $isUpdate = (bool) $this->current?->id;
        if ($isUpdate && !adminCan('hrm_leaves.update')) {
            abort(403);
        }
        if (!$isUpdate && !adminCan('hrm_leaves.create')) {
            abort(403);
        }

        $this->validate([
            'data.name' => 'required|string|max:255',
            'data.yearly_allowance' => 'required|numeric|min:0',
            'data.is_paid' => 'boolean',
        ]);

        try {
            DB::beginTransaction();
            if ($isUpdate) {
                $this->leaveTypeService->update($this->current->id, $this->data);
            } else {
                $this->leaveTypeService->create($this->data);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Error while saving leave type: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Leave type saved successfully');
        $this->dismiss();
        $this->reset('current', 'data');
        $this->dispatch('re-render');
    }

    public function render()
    {
        return view('livewire.admin.hrm.leaves.leave-type-modal');
    }
}
