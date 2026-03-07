<?php

namespace App\Models\Tenant;

use App\Enums\AttendanceLogSourceEnum;
use App\Enums\AttendanceLogStatusEnum;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'attendance_sheet_id',
        'employee_id',
        'clock_in_at',
        'clock_out_at',
        'status',
        'source',
    ];

    protected $casts = [
        'clock_in_at' => 'datetime',
        'clock_out_at' => 'datetime',
        'status' => AttendanceLogStatusEnum::class,
        'source' => AttendanceLogSourceEnum::class,
    ];

    public function sheet()
    {
        return $this->belongsTo(AttendanceSheet::class, 'attendance_sheet_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }
}
