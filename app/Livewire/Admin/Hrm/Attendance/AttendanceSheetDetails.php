<?php

namespace App\Livewire\Admin\Hrm\Attendance;

use App\Models\Tenant\AttendanceLog;
use App\Models\Tenant\AttendanceSheet;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceSheetDetails extends Component
{
    use WithPagination;

    public int $sheetId;

    public function mount($id): void
    {
        $this->sheetId = (int) $id;
    }

    public function render()
    {
        $sheet = AttendanceSheet::query()->with(['department'])->findOrFail($this->sheetId);
        $logs = AttendanceLog::query()
            ->with(['employee'])
            ->where('attendance_sheet_id', $sheet->id)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return layoutView('hrm.attendance.attendance-sheet-details', get_defined_vars())
            ->title('Attendance Sheet');
    }
}
