<?php

namespace App\Livewire\Admin\Hrm\Attendance;

use App\Services\Hrm\AttendanceSheetService;
use App\Traits\LivewireOperations;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceSheetsList extends Component
{
    use LivewireOperations, WithPagination;

    private AttendanceSheetService $attendanceSheetService;

    public array $filters = [];
    public bool $collapseFilters = false;
    public $current;

    public function boot(): void
    {
        $this->attendanceSheetService = app(AttendanceSheetService::class);
    }

    public function setCurrent($id): void
    {
        $this->current = $this->attendanceSheetService->getById($id);
    }

    public function deleteAlert($id): void
    {
        if (!adminCan('hrm_attendance.delete')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this attendance sheet', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!adminCan('hrm_attendance.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Attendance sheet not found');
            return;
        }
        $this->attendanceSheetService->delete($this->current->id);
        $this->popup('success', 'Attendance sheet deleted successfully');
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function submitAlert($id): void
    {
        if (!adminCan('hrm_attendance.update')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('submit', 'question', 'Submit?', 'You want to submit this attendance sheet', 'Yes, submit');
    }

    public function approveAlert($id): void
    {
        if (!adminCan('hrm_attendance.approve')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('approve', 'question', 'Approve?', 'You want to approve this attendance sheet', 'Yes, approve');
    }

    public function submit(): void
    {
        if (!adminCan('hrm_attendance.update')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Attendance sheet not found');
            return;
        }
        if (($this->current->status ?? null) !== 'draft') {
            $this->popup('warning', 'Only draft sheets can be submitted');
            $this->dismiss();
            return;
        }

        $this->attendanceSheetService->update($this->current->id, [
            'status' => 'submitted',
        ]);

        $this->popup('success', 'Attendance sheet submitted');
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    public function approve(): void
    {
        if (!adminCan('hrm_attendance.approve')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', 'Attendance sheet not found');
            return;
        }
        if (($this->current->status ?? null) !== 'submitted') {
            $this->popup('warning', 'Only submitted sheets can be approved');
            $this->dismiss();
            return;
        }

        $this->attendanceSheetService->update($this->current->id, [
            'status' => 'approved',
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', 'Attendance sheet approved');
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    #[On('re-render')]
    public function render()
    {
        $sheets = $this->attendanceSheetService->list(['department'], $this->filters, 10, 'id');

        return layoutView('hrm.attendance.attendance-sheets-list', get_defined_vars())
            ->title('HRM Attendance Sheets');
    }
}
