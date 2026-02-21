<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class AttendanceSheet extends Model
{
    protected $fillable = [
        'date',
        'department_id',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['department_id'] ?? null, fn($q, $departmentId) => $q->where('department_id', $departmentId))
            ->when($filters['date'] ?? null, fn($q, $date) => $q->whereDate('date', $date))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status));
    }
}
