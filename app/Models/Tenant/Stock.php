<?php

namespace App\Models\Tenant;

use App\Models\Tenant\ProductVariable;
use App\Models\Tenant\Unit;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['product_id','unit_id','qty','sell_price'];

    function product() {
        return $this->belongsTo(Product::class,'product_id');
    }

    function unit() {
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
