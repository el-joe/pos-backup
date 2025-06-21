<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class SaleVariable extends Model
{
    protected $fillable = ['sale_id', 'product_variable_id', 'unit_id', 'qty', 'sale_price', 'discount_type', 'discount','refunded','refunded_at'];

    public function sale() {
        return $this->belongsTo(Sale::class);
    }

    public function productVariable() {
        return $this->belongsTo(ProductVariable::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }
}
