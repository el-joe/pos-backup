<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'ar_name',
        'description',
        'ar_description',
        'days_per_year',
        'is_paid',
        'carry_forward',
        'max_carry_forward_days',
        'requires_document',
        'max_consecutive_days',
        'min_days_notice',
        'color',
        'active',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'carry_forward' => 'boolean',
        'requires_document' => 'boolean',
        'active' => 'boolean',
    ];

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function entitlements(): HasMany
    {
        return $this->hasMany(EmployeeLeaveEntitlement::class);
    }

    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->ar_name ? $this->ar_name : $value;
    }
}
