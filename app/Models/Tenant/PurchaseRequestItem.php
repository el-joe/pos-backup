<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    protected $fillable = [
        'purchase_request_id',
        'product_id',
        'unit_id',
        'qty',
        'purchase_price',
        'discount_percentage',
        'tax_percentage',
        'x_margin',
        'sell_price',
    ];

    public function request()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withTrashed();
    }

    public function getUnitCostAfterDiscountAttribute(): float
    {
        $purchasePrice = (float) $this->purchase_price;
        $discountPercentage = (float) ($this->discount_percentage ?? 0);
        return $purchasePrice - ($purchasePrice * $discountPercentage / 100);
    }

    public function getTotalNetCostAttribute(): float
    {
        return $this->unit_cost_after_discount * (float) $this->qty;
    }

    public function getTaxAmountAttribute(): float
    {
        $taxPercentage = (float) ($this->tax_percentage ?? 0);
        return $this->total_net_cost * ($taxPercentage / 100);
    }

    public function getTotalAfterTaxAttribute(): float
    {
        return $this->total_net_cost + $this->tax_amount;
    }
}
