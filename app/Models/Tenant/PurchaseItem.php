<?php

namespace App\Models\Tenant;

use App\Models\Tenant\ProductVariable;
use App\Models\Tenant\Unit;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id','product_id','unit_id','qty','purchase_price','discount_percentage','tax_percentage','x_margin','sell_price','returned','returned_at'
    ];

    function purchase() {
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    function product() {
        return $this->belongsTo(Product::class,'product_id');
    }

    function unit() {
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
