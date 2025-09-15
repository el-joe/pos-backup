<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'product_variable_id', 'unit_id', 'qty', 'sell_price', 'discount_type', 'discount','refunded','refunded_at'];

    public function sale() {
        return $this->belongsTo(Sale::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }
}
