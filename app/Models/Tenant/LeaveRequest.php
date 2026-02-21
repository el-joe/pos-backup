<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class)->withTrashed();
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['employee_id'] ?? null, fn($q, $employeeId) => $q->where('employee_id', $employeeId))
            ->when($filters['status'] ?? null, function ($q, $status) {
                if ($status !== 'all') {
                    $q->where('status', $status);
                }
            })
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('reason', 'like', "%{$search}%");
            });
    }
}
