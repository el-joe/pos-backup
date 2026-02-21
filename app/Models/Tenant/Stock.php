<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Unit;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['product_id','unit_id','branch_id','unit_cost','qty','sell_price'];

    function product() {
        return $this->belongsTo(Product::class,'product_id')->withTrashed();
    }

    function unit() {
        return $this->belongsTo(Unit::class,'unit_id')->withTrashed();
    }

    function branch() {
        return $this->belongsTo(Branch::class,'branch_id')->withTrashed();
    }

    function scopeFilter($query, $filters = []) {
        return $query->when($filters['branch_id'] ?? null, function($q) use ($filters) {
            $q->where('branch_id', $filters['branch_id']);
        })->when($filters['product_id'] ?? null, function($q) use ($filters) {
            $q->where('product_id', $filters['product_id']);
        })->when($filters['unit_id'] ?? null, function($q) use ($filters) {
            $q->where('unit_id', $filters['unit_id']);
        })->when($filters['id'] ?? null, function($q) use ($filters) {
            $q->where('id', $filters['id']);
        });
    }
}
