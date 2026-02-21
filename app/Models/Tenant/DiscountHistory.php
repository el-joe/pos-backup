<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class DiscountHistory extends Model
{
    public $fillable = [
        'discount_id',
        'target_type',
        'target_id',
    ];

    public static $relatedWith = [
        Sale::class => 'sales'
    ];

    function discount() {
        return $this->belongsTo(Discount::class)->withTrashed();
    }

    function target() {
        return $this->morphTo();
    }
}
