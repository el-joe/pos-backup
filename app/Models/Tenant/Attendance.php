<?php

namespace App\Models\Tenant;

use App\Enums\AttendanceStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'branch_id',
        'date',
        'clock_in',
        'clock_out',
        'working_hours',
        'overtime_hours',
        'late_minutes',
        'early_leaving_minutes',
        'status',
        'remarks',
        'clock_in_location',
        'clock_out_location',
        'clock_in_ip',
        'clock_out_ip',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'approved_at' => 'datetime',
        'status' => AttendanceStatusEnum::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
