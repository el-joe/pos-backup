<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use SoftDeletes;

    protected $table = 'workers';

    protected $fillable = [
        'code',
        'name',
        'type',
        'national_id',
        'phone',
        'daily_rate',
        'monthly_salary',
        'hire_date',
        'is_active',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'daily_rate' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function timesheets()
    {
        return $this->hasMany(LaborTimesheet::class);
    }
}
