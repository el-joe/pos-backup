<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAsset extends Model
{
    use SoftDeletes;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_UNDER_CONSTRUCTION = 'under_construction';
    public const STATUS_DISPOSED = 'disposed';
    public const STATUS_SOLD = 'sold';

    public const METHOD_STRAIGHT_LINE = 'straight_line';
    public const METHOD_DECLINING_BALANCE = 'declining_balance';
    public const METHOD_DOUBLE_DECLINING_BALANCE = 'double_declining_balance';

    protected $fillable = [
        'created_by',
        'branch_id',
        'code',
        'name',
        'purchase_date',
        'cost',
        'salvage_value',
        'useful_life_months',
        'depreciation_rate',
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
        'depreciation_rate' => 'decimal:4',
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

    public function depreciationExpenses()
    {
        return $this->morphMany(Expense::class, 'model')
            ->where(function ($q) {
                $q->where('fixed_asset_entry_type', 'depreciation')
                    ->orWhereNull('fixed_asset_entry_type');
            });
    }

    public function repairExpenses()
    {
        return $this->morphMany(Expense::class, 'model')
            ->where('fixed_asset_entry_type', 'repair_expense');
    }

    public function lifespanExtensions()
    {
        return $this->hasMany(FixedAssetExtension::class, 'fixed_asset_id');
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
        if ($this->is_under_construction) {
            return 0.0;
        }

        return $this->calculateMonthlyDepreciation(now());
    }

    public function getRecordedAccumulatedDepreciationAttribute(): float
    {
        return (float) $this->depreciationExpenses()
            ->sum('amount');
    }

    public function getCalculatedAccumulatedDepreciationAttribute(): float
    {
        return $this->calculateAccumulatedDepreciation(now());
    }

    public function getAccumulatedDepreciationAttribute(): float
    {
        $recorded = (float) $this->recorded_accumulated_depreciation;
        $calculated = (float) $this->calculated_accumulated_depreciation;

        return max($recorded, $calculated);
    }

    public function getNetBookValueAttribute(): float
    {
        return max(0.0, (float) ($this->cost ?? 0) - (float) $this->accumulated_depreciation);
    }

    public function getIsUnderConstructionAttribute(): bool
    {
        if (($this->status ?? null) === self::STATUS_UNDER_CONSTRUCTION) {
            return true;
        }

        $startDate = $this->depreciation_start_date;
        if (!$startDate) {
            return true;
        }

        return Carbon::parse((string) $startDate)->isFuture();
    }

    public function calculateAccumulatedDepreciation(?Carbon $asOf = null): float
    {
        $asOfDate = ($asOf ?? now())->copy()->startOfMonth();

        if ($this->is_under_construction) {
            return 0.0;
        }

        $startDate = $this->depreciation_start_date ? Carbon::parse($this->depreciation_start_date)->startOfMonth() : null;
        if (!$startDate || $startDate->gt($asOfDate)) {
            return 0.0;
        }

        $monthsElapsed = ($startDate->diffInMonths($asOfDate)) + 1;
        $lifeMonths = max(0, (int) ($this->useful_life_months ?? 0));
        if ($lifeMonths <= 0) {
            return 0.0;
        }

        $monthsElapsed = min($monthsElapsed, $lifeMonths);
        $cost = (float) ($this->cost ?? 0);
        $salvage = max(0.0, (float) ($this->salvage_value ?? 0));
        $depreciableBase = max(0.0, $cost - $salvage);
        if ($depreciableBase <= 0) {
            return 0.0;
        }

        $method = (string) ($this->depreciation_method ?? self::METHOD_STRAIGHT_LINE);
        if ($method === self::METHOD_STRAIGHT_LINE) {
            $monthly = $depreciableBase / $lifeMonths;
            return min($depreciableBase, max(0.0, $monthly * $monthsElapsed));
        }

        $carryingValue = $cost;
        $accumulated = 0.0;

        for ($i = 0; $i < $monthsElapsed; $i++) {
            $monthly = $this->calculateDecliningMonthlyAmount($carryingValue, $lifeMonths, $method);
            if ($monthly <= 0) {
                break;
            }

            $maxAllowed = max(0.0, $carryingValue - $salvage);
            $monthly = min($monthly, $maxAllowed);
            if ($monthly <= 0) {
                break;
            }

            $accumulated += $monthly;
            $carryingValue -= $monthly;
        }

        return min($depreciableBase, max(0.0, $accumulated));
    }

    public function calculateMonthlyDepreciation(?Carbon $asOf = null): float
    {
        $asOfDate = ($asOf ?? now())->copy()->startOfMonth();
        $startDate = $this->depreciation_start_date ? Carbon::parse($this->depreciation_start_date)->startOfMonth() : null;
        if ($this->is_under_construction || !$startDate || $startDate->gt($asOfDate)) {
            return 0.0;
        }

        $lifeMonths = max(0, (int) ($this->useful_life_months ?? 0));
        if ($lifeMonths <= 0) {
            return 0.0;
        }

        $monthsElapsed = min(($startDate->diffInMonths($asOfDate)) + 1, $lifeMonths);
        $cost = (float) ($this->cost ?? 0);
        $salvage = max(0.0, (float) ($this->salvage_value ?? 0));
        $depreciableBase = max(0.0, $cost - $salvage);
        if ($depreciableBase <= 0) {
            return 0.0;
        }

        $method = (string) ($this->depreciation_method ?? self::METHOD_STRAIGHT_LINE);
        if ($method === self::METHOD_STRAIGHT_LINE) {
            return min($depreciableBase, max(0.0, $depreciableBase / $lifeMonths));
        }

        $carryingValue = $cost;
        $currentMonth = 0.0;
        for ($i = 0; $i < $monthsElapsed; $i++) {
            $monthly = $this->calculateDecliningMonthlyAmount($carryingValue, $lifeMonths, $method);
            $maxAllowed = max(0.0, $carryingValue - $salvage);
            $monthly = min(max(0.0, $monthly), $maxAllowed);
            $carryingValue -= $monthly;
            $currentMonth = $monthly;
        }

        return max(0.0, $currentMonth);
    }

    private function calculateDecliningMonthlyAmount(float $carryingValue, int $lifeMonths, string $method): float
    {
        if ($carryingValue <= 0 || $lifeMonths <= 0) {
            return 0.0;
        }

        $annualRate = (float) ($this->depreciation_rate ?? 0);
        if ($annualRate <= 0) {
            $annualRate = 100 / max(1.0, ($lifeMonths / 12));
        }

        if ($method === self::METHOD_DOUBLE_DECLINING_BALANCE) {
            $annualRate *= 2;
        }

        $monthlyRate = max(0.0, $annualRate / 100 / 12);
        return max(0.0, $carryingValue * $monthlyRate);
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
