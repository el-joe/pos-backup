<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    protected $fillable = [
        'month',
        'year',
        'status',
        'total_payout',
        'transaction_id',
    ];

    protected $casts = [
        'total_payout' => 'decimal:2',
    ];

    public function slips()
    {
        return $this->hasMany(PayrollSlip::class);
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['month'] ?? null, fn($q, $month) => $q->where('month', $month))
            ->when($filters['year'] ?? null, fn($q, $year) => $q->where('year', $year))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status));
    }
}
