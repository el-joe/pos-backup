<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLeaveEntitlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'total_days',
        'used_days',
        'pending_days',
        'remaining_days',
        'carried_forward_days',
    ];

    protected $casts = [
        'total_days' => 'decimal:2',
        'used_days' => 'decimal:2',
        'pending_days' => 'decimal:2',
        'remaining_days' => 'decimal:2',
        'carried_forward_days' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
