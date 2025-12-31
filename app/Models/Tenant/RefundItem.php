<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class RefundItem extends Model
{
    protected $fillable = [
        'refund_id',
        'refundable_type',
        'refundable_id',
        'product_id',
        'unit_id',
        'qty',
    ];

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function refundable()
    {
        return $this->morphTo();
    }
}
