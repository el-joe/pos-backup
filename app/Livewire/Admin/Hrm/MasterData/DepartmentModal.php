<?php

namespace App\Livewire\Admin\Hrm\MasterData;

use App\Services\Hrm\DepartmentService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class DepartmentModal extends Component
{
    use LivewireOperations;

    public $current;
    public array $data = [
        'name' => null,
        'parent_id' => null,
        'manager_id' => null,
    ];

    private DepartmentService $departmentService;

    public function boot(): void
    {
        $this->departmentService = app(DepartmentService::class);
    }

    #[On('hrm-department-set-current')]
    public function setCurrent($id = null): void
    {
        $this->current = $this->departmentService->find($id);
        if ($this->current) {
            $this->data = [
                'name' => $this->current->name,
                'parent_id' => $this->current->parent_id,
                'manager_id' => $this->current->manager_id,
            ];
        } else {
            $this->reset('data', 'current');
        }
    }

    public function save(): void
    {
        $isUpdate = (bool) $this->current?->id;
        if ($isUpdate && !adminCan('hrm_master_data.update')) {
            abort(403);
        }
        if (!$isUpdate && !adminCan('hrm_master_data.create')) {
            abort(403);
        }

        $this->validate([
            'data.name' => 'required|string|max:255',
            'data.parent_id' => 'nullable|exists:departments,id',
            'data.manager_id' => 'nullable|exists:employees,id',
        ]);

        try {
            DB::beginTransaction();
            if ($isUpdate) {
                $this->departmentService->update($this->current->id, $this->data);
            } else {
                $this->departmentService->create($this->data);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Error while saving department: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Department saved successfully');
        $this->dismiss();
        $this->reset('current', 'data');
        $this->dispatch('re-render');
    }

    public function render()
    {
        return view('livewire.admin.hrm.master-data.department-modal');
    }
}
