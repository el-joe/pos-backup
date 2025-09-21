<?php

namespace App\Models\Tenant;

use App\Models\Tenant\ProductVariable;
use App\Models\Tenant\Unit;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id','product_id','unit_id','qty','purchase_price','discount_percentage','tax_percentage','x_margin','sell_price','refunded_qty','refunded_at',
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

    function getTotalAfterTaxAttribute() {
        $total = ($this->qty - $this->refunded_qty) * $this->purchase_price;
        $discountAmount = ($total * ($this->discount_percentage ?? 0) / 100);
        $totalAfterDiscount = $total - $discountAmount;
        $taxAmount = ($totalAfterDiscount * ($this->tax_percentage ?? 0) / 100);

        return $totalAfterDiscount + $taxAmount;
    }
}
