<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'ar_name',
        'description',
        'ar_description',
        'department_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function salaryStructures(): HasMany
    {
        return $this->hasMany(SalaryStructure::class);
    }

    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->ar_name ? $this->ar_name : $value;
    }
}
