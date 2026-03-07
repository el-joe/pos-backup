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
        $this->confirm('delete', 'warning', __('general.messages.are_you_sure'), __('general.messages.hrm.confirm_delete_attendance_sheet'), __('general.messages.yes_delete_it'));
    }

    public function delete(): void
    {
        if (!adminCan('hrm_attendance.delete')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.attendance_sheet_not_found'));
            return;
        }
        $this->attendanceSheetService->delete($this->current->id);
        $this->popup('success', __('general.messages.hrm.attendance_sheet_deleted_successfully'));
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
        $this->confirm('submit', 'question', __('general.pages.hrm.submit_action'), __('general.messages.hrm.confirm_submit_attendance_sheet'), __('general.pages.hrm.submit_action'));
    }

    public function approveAlert($id): void
    {
        if (!adminCan('hrm_attendance.approve')) {
            abort(403);
        }
        $this->setCurrent($id);
        $this->confirm('approve', 'question', __('general.pages.hrm.approve_action'), __('general.messages.hrm.confirm_approve_attendance_sheet'), __('general.pages.hrm.approve_action'));
    }

    public function submit(): void
    {
        if (!adminCan('hrm_attendance.update')) {
            abort(403);
        }
        if (!$this->current) {
            $this->popup('error', __('general.messages.hrm.attendance_sheet_not_found'));
            return;
        }
        if (($this->current->status ?? null) !== 'draft') {
            $this->popup('warning', __('general.messages.hrm.only_draft_attendance_sheets_can_be_submitted'));
            $this->dismiss();
            return;
        }

        $this->attendanceSheetService->update($this->current->id, [
            'status' => 'submitted',
        ]);

        $this->popup('success', __('general.messages.hrm.attendance_sheet_submitted'));
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
            $this->popup('error', __('general.messages.hrm.attendance_sheet_not_found'));
            return;
        }
        if (($this->current->status ?? null) !== 'submitted') {
            $this->popup('warning', __('general.messages.hrm.only_submitted_attendance_sheets_can_be_approved'));
            $this->dismiss();
            return;
        }

        $this->attendanceSheetService->update($this->current->id, [
            'status' => 'approved',
            'approved_by' => admin()->id,
            'approved_at' => Carbon::now(),
        ]);

        $this->popup('success', __('general.messages.hrm.attendance_sheet_approved'));
        $this->dismiss();
        $this->reset('current');
        $this->dispatch('re-render');
    }

    #[On('re-render')]
    public function render()
    {
        $sheets = $this->attendanceSheetService->list(['department'], $this->filters, 10, 'id');

        return layoutView('hrm.attendance.attendance-sheets-list', get_defined_vars())
            ->title(__('general.titles.hrm_attendance_sheets'));
    }
}
