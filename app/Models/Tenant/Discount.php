<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes;
    public $fillable = [
        'name',
        'code',
        'type',
        'value',
        'max_discount_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'active',
        'branch_id',
        'sales_threshold'
    ];

    function history() {
        return $this->hasMany(DiscountHistory::class,'discount_id');
    }


    function scopeValid($q) {
        $q->where('active', 1)
            ->where(function($q) {
                $q->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', date('Y-m-d'));
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', date('Y-m-d'));
            });
    }
}
