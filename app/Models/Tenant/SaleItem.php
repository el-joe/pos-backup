<?php

namespace App\Models\Tenant;

use App\Helpers\SaleHelper;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'unit_id' , 'product_id', 'qty' , 'taxable','unit_cost', 'sell_price' ,'refunded_qty','refunded_at'];

    public function sale() {
        return $this->belongsTo(Sale::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    function getActualQtyAttribute() : int {
        return $this->qty - $this->refunded_qty;

    }

    function getTotalAttribute()  {
        return SaleHelper::itemTotal($this);
    }
}
