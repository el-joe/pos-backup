<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'ar_name',
        'description',
        'designation_id',
        'basic_salary',
        'allowances',
        'deductions',
        'gross_salary',
        'net_salary',
        'currency',
        'active',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'allowances' => 'array',
        'deductions' => 'array',
        'active' => 'boolean',
    ];

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function employeeSalaries(): HasMany
    {
        return $this->hasMany(EmployeeSalary::class);
    }
}
