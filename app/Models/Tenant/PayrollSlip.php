<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PayrollSlip extends Model
{
    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'gross_pay',
        'net_pay',
    ];

    protected $casts = [
        'gross_pay' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function run()
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function lines()
    {
        return $this->hasMany(PayrollSlipLine::class);
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['employee_id'] ?? null, fn($q, $employeeId) => $q->where('employee_id', $employeeId))
            ->when($filters['payroll_run_id'] ?? null, fn($q, $runId) => $q->where('payroll_run_id', $runId));
    }
}

