<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'ar_name',
        'start_time',
        'end_time',
        'working_hours',
        'break_duration',
        'grace_period',
        'working_days',
        'active',
    ];

    protected $casts = [
        'working_days' => 'array',
        'active' => 'boolean',
    ];

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->ar_name ? $this->ar_name : $value;
    }
}
