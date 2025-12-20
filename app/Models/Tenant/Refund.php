<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'order_type',
        'order_id',
        'total',
        'reason',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(RefundItem::class);
    }

    public function order()
    {
        return $this->morphTo();
    }
}
