<?php

namespace App\Livewire\Admin\Hrm\Attendance;

use App\Services\Hrm\AttendanceSheetService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class AttendanceSheetModal extends Component
{
    use LivewireOperations;

    private AttendanceSheetService $attendanceSheetService;

    public $current;
    public array $data = [
        'date' => null,
        'department_id' => null,
        'status' => 'draft',
    ];

    public function boot(): void
    {
        $this->attendanceSheetService = app(AttendanceSheetService::class);
    }

    #[On('hrm-attendance-sheet-set-current')]
    public function setCurrent($id = null): void
    {
        $this->current = $this->attendanceSheetService->getById($id);
        if ($this->current) {
            $this->data = [
                'date' => optional($this->current->date)->format('Y-m-d'),
                'department_id' => $this->current->department_id,
                'status' => $this->current->status,
            ];
        } else {
            $this->reset('current', 'data');
            $this->data = [
                'date' => null,
                'department_id' => null,
                'status' => 'draft',
            ];
        }
    }

    public function save(): void
    {
        $isUpdate = (bool) $this->current?->id;
        if ($isUpdate && !adminCan('hrm_attendance.update')) {
            abort(403);
        }
        if (!$isUpdate && !adminCan('hrm_attendance.create')) {
            abort(403);
        }

        $this->validate([
            'data.date' => 'required|date',
            'data.department_id' => 'nullable|exists:departments,id',
            'data.status' => 'required|string|max:50',
        ]);

        try {
            DB::beginTransaction();
            if ($isUpdate) {
                $this->attendanceSheetService->update($this->current->id, $this->data);
            } else {
                $this->attendanceSheetService->create($this->data);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->popup('error', 'Error while saving attendance sheet: ' . $e->getMessage());
            return;
        }

        $this->popup('success', 'Attendance sheet saved successfully');
        $this->dismiss();
        $this->reset('current', 'data');
        $this->dispatch('re-render');
    }

    public function render()
    {
        return view('livewire.admin.hrm.attendance.attendance-sheet-modal');
    }
}
