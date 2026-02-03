<?php

namespace App\Models\Tenant;

use App\Enums\LeaveStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'is_half_day',
        'half_day_period',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'approved_remarks',
        'approved_at',
        'rejected_by',
        'rejection_reason',
        'rejected_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_days' => 'decimal:2',
        'is_half_day' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'status' => LeaveStatusEnum::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function rejector(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'rejected_by');
    }
}
