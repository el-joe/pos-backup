<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'branch_id',
        'code',
        'name',
        'purchase_date',
        'cost',
        'salvage_value',
        'useful_life_months',
        'depreciation_method',
        'depreciation_start_date',
        'status',
        'note',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'depreciation_start_date' => 'date',
        'cost' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'useful_life_months' => 'integer',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by')->withTrashed();
    }

    public function expenses()
    {
        return $this->morphMany(Expense::class, 'model');
    }

    public static function generateCode(): string
    {
        $last = self::orderByDesc('id')->first();
        if ($last) {
            return 'FA-' . str_pad((int) str_replace('FA-', '', $last->code) + 1, 6, '0', STR_PAD_LEFT);
        }
        return 'FA-000001';
    }

    public function getMonthlyDepreciationAttribute(): float
    {
        $life = (int) ($this->useful_life_months ?? 0);
        if ($life <= 0) {
            return 0.0;
        }

        $depreciable = (float) ($this->cost ?? 0) - (float) ($this->salvage_value ?? 0);
        return max(0.0, $depreciable / $life);
    }

    public function getAccumulatedDepreciationAttribute(): float
    {
        return (float) $this->expenses()
            ->sum('amount');
    }

    public function getNetBookValueAttribute(): float
    {
        return max(0.0, (float) ($this->cost ?? 0) - (float) $this->accumulated_depreciation);
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['branch_id'] ?? null, fn ($q, $v) => $q->where('branch_id', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['search'] ?? null, function ($q, $v) {
                $q->where(function ($q) use ($v) {
                    $q->where('code', 'like', '%' . $v . '%')
                        ->orWhere('name', 'like', '%' . $v . '%');
                });
            })
            ->when($filters['id'] ?? null, fn ($q, $v) => $q->where('id', $v));
    }
}
