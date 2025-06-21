<?php

namespace App\Models\Tenant;

use App\Models\Tenant\ProductVariable;
use App\Models\Tenant\Unit;
use Illuminate\Database\Eloquent\Model;

class PurchaseVariable extends Model
{
    protected $fillable = [
        'purchase_id','product_variable_id','unit_id','qty','purchase_price','discount_percentage',
        'price','total','x_margin','sale_price','returned','returned_at'
    ];

    function purchase() {
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    function productVariable() {
        return $this->belongsTo(ProductVariable::class,'product_variable_id');
    }

    function unit() {
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
