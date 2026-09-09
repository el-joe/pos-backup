<?php

namespace App\Models\Tenant;

use App\Enums\PayrollSlipLineTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollComponent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'type',
        'amount',
        'description',
        'is_recurring',
        'month',
        'year',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'type' => PayrollSlipLineTypeEnum::class,
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['employee_id'] ?? null, fn($q, $employeeId) => $q->where('employee_id', $employeeId))
            ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type instanceof PayrollSlipLineTypeEnum ? $type->value : $type));
    }

    /** Components applicable to a given month/year: active recurring ones within their
     *  start/end window, plus one-off components pinned to that exact month/year. */
    public function scopeApplicableFor($q, int $employeeId, int $month, int $year)
    {
        return $q->where('employee_id', $employeeId)
            ->where('is_active', true)
            ->where(function ($q) use ($month, $year) {
                $periodStart = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
                $periodEnd = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

                $q->where(function ($q) use ($periodStart, $periodEnd) {
                    $q->where('is_recurring', true)
                        ->where(fn($q) => $q->whereNull('start_date')->orWhere('start_date', '<=', $periodEnd))
                        ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', $periodStart));
                })->orWhere(function ($q) use ($month, $year) {
                    $q->where('is_recurring', false)
                        ->where('month', $month)
                        ->where('year', $year);
                });
            });
    }
}
