<?php

namespace App\Models\Tenant;

use App\Helpers\SaleHelper;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\SaleRequest;
use App\Models\Tenant\Product;
use App\Models\Tenant\Unit;

class SaleRequestItem extends Model
{
    protected $fillable = [
        'sale_request_id',
        'product_id',
        'unit_id',
        'qty',
        'taxable',
        'unit_cost',
        'sell_price',
    ];

    protected $casts = [
        'taxable' => 'boolean',
    ];

    public function request()
    {
        return $this->belongsTo(SaleRequest::class, 'sale_request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withTrashed();
    }

    public function getTotalDiscountAmountAttribute(): float
    {
        $request = $this->request;
        if (!$request) {
            return 0;
        }

        return (float) SaleHelper::singleDiscountAmount(
            $this,
            clone $request->items,
            $request->discount_type,
            $request->discount_value,
            $request->max_discount_amount ?? 0
        );
    }
}
