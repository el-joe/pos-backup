<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceKPI extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'performance_kpis';

    protected $fillable = [
        'name',
        'ar_name',
        'description',
        'ar_description',
        'department_id',
        'designation_id',
        'measurement_unit',
        'target_value',
        'weightage',
        'active',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->ar_name ? $this->ar_name : $value;
    }
}
