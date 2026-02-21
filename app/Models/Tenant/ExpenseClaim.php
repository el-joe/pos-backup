<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ExpenseClaim extends Model
{
    protected $fillable = [
        'employee_id',
        'claim_date',
        'total_amount',
        'status',
        'approved_by',
        'approved_at',
        'transaction_id',
    ];

    protected $casts = [
        'claim_date' => 'date',
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function lines()
    {
        return $this->hasMany(ExpenseClaimLine::class);
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['employee_id'] ?? null, fn($q, $employeeId) => $q->where('employee_id', $employeeId))
            ->when($filters['status'] ?? null, function ($q, $status) {
                if ($status !== 'all') {
                    $q->where('status', $status);
                }
            });
    }
}
