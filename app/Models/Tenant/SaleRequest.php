<?php

namespace App\Models\Tenant;

use App\Enums\SaleRequestStatusEnum;
use App\Helpers\SaleHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'customer_id',
        'branch_id',
        'quote_number',
        'request_date',
        'valid_until',
        'status',
        'tax_id',
        'tax_percentage',
        'discount_id',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'note',
    ];

    protected $casts = [
        'status' => SaleRequestStatusEnum::class,
        'request_date' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(SaleRequestItem::class, 'sale_request_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by')->withTrashed();
    }

    public static function generateQuoteNumber(): string
    {
        $last = self::latest()->first();
        if ($last) {
            return 'QTN-' . str_pad((int) str_replace('QTN-', '', $last->quote_number) + 1, 6, '0', STR_PAD_LEFT);
        }
        return 'QTN-000001';
    }

    public function getSubTotalAttribute(): float
    {
        return (clone $this->items)->sum(fn ($item) => SaleHelper::itemTotal($item));
    }

    public function getDiscountAmountAttribute(): float
    {
        return (clone $this->items)->map(fn ($item) => (float) ($item->total_discount_amount ?? 0))->sum();
    }

    public function getTaxAmountAttribute(): float
    {
        $items = clone $this->items;
        return (clone $items)->map(function ($item) use ($items) {
            return SaleHelper::singleTaxAmount($item, clone $items, $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
        })->sum();
    }

    public function getGrandTotalAmountAttribute(): float
    {
        $items = clone $this->items;
        return (clone $items)->map(function ($item) use ($items) {
            return SaleHelper::singleGrandTotal($item, clone $items, $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
        })->sum();
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q->when($filters['branch_id'] ?? null, fn ($q, $v) => $q->where('branch_id', $v))
            ->when($filters['customer_id'] ?? null, fn ($q, $v) => $q->where('customer_id', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['from_date'] ?? null, fn ($q, $v) => $q->whereDate('request_date', '>=', $v))
            ->when($filters['to_date'] ?? null, fn ($q, $v) => $q->whereDate('request_date', '<=', $v))
            ->when($filters['search'] ?? null, function ($q, $v) {
                $q->where(function ($q) use ($v) {
                    $q->where('quote_number', 'like', '%' . $v . '%')
                        ->orWhereHas('customer', fn ($q) => $q->where('name', 'like', '%' . $v . '%'));
                });
            })
            ->when($filters['id'] ?? null, fn ($q, $v) => $q->where('id', $v));
    }
}
