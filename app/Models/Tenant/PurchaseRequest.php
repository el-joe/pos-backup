<?php

namespace App\Models\Tenant;

use App\Enums\PurchaseRequestStatusEnum;
use App\Helpers\PurchaseHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'supplier_id',
        'branch_id',
        'request_number',
        'request_date',
        'status',
        'tax_id',
        'tax_percentage',
        'discount_type',
        'discount_value',
        'note',
    ];

    protected $casts = [
        'status' => PurchaseRequestStatusEnum::class,
        'request_date' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class, 'purchase_request_id');
    }

    public static function generateRequestNumber(): string
    {
        $last = self::latest()->first();
        if ($last) {
            return 'PR-' . str_pad((int) str_replace('PR-', '', $last->request_number) + 1, 6, '0', STR_PAD_LEFT);
        }
        return 'PR-000001';
    }

    public function getItemsTotalAmountAttribute(): float
    {
        return (clone $this->items)->sum(fn ($q) => (float) ($q->total_after_tax ?? 0));
    }

    public function getTotalAmountAttribute(): float
    {
        $totalItems = $this->items_total_amount;
        $subTotal = PurchaseHelper::calcSubTotal($totalItems, 0);
        $discountAmount = PurchaseHelper::calcDiscount($subTotal, $this->discount_type, $this->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($subTotal, $discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount, $this->tax_percentage);

        return PurchaseHelper::calcGrandTotal($totalAfterDiscount, $taxAmount);
    }

    public function scopeFilter($q, array $filters = [])
    {
        return $q->when($filters['id'] ?? false, fn ($q, $id) => $q->where('id', $id))
            ->when($filters['request_number'] ?? false, fn ($q, $v) => $q->where('request_number', 'like', '%' . $v . '%'))
            ->when($filters['supplier_id'] ?? false, fn ($q, $v) => $q->where('supplier_id', $v))
            ->when($filters['branch_id'] ?? false, fn ($q, $v) => $q->where('branch_id', $v))
            ->when($filters['status'] ?? false, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['date_from'] ?? false, fn ($q, $v) => $q->whereDate('request_date', '>=', $v))
            ->when($filters['date_to'] ?? false, fn ($q, $v) => $q->whereDate('request_date', '<=', $v));
    }
}
