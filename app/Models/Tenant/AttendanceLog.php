<?php

namespace App\Models\Tenant;

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
