<?php

namespace App\Livewire\Admin\Hrm\MasterData;

use App\Services\Hrm\DepartmentService;
use App\Services\Hrm\DesignationService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class DesignationModal extends Component
{
    use LivewireOperations;

    public $current;
    public array $data = [
        'title' => null,
        'department_id' => null,
        'base_salary_range' => null,
    ];

    private DesignationService $designationService;
    private DepartmentService $departmentService;

    public function boot(): void
    {
        $this->designationService = app(DesignationService::class);
        $this->departmentService = app(DepartmentService::class);
    }

    #[On('hrm-designation-set-current')]
    public function setCurrent($id = null): void
    {
        $this->current = $this->designationService->find($id);
        if ($this->current) {
            $this->data = [
                'title' => $this->current->title,
                'department_id' => $this->current->department_id,
                'base_salary_range' => $this->current->base_salary_range,
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
            'data.title' => 'required|string|max:255',
            'data.department_id' => 'nullable|exists:departments,id',
            'data.base_salary_range' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            if ($isUpdate) {
                $this->designationService->update($this->current->id, $this->data);
            } else {
                $this->designationService->create($this->data);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Error while saving designation: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Designation saved successfully');
        $this->dismiss();
        $this->reset('current', 'data');
        $this->dispatch('re-render');
    }

    public function render()
    {
        $departments = $this->departmentService->list([], [], null, 'name');
        return view('livewire.admin.hrm.master-data.designation-modal', get_defined_vars());
    }
}
