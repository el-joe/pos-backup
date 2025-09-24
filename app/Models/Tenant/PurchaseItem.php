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

    function getActualQtyAttribute() {
        return $this->qty - $this->refunded_qty;
    }

    function getUnitCostAfterDiscountAttribute() : float {
        $discountAmount = ($this->purchase_price * ($this->discount_percentage ?? 0) / 100);
        return $this->purchase_price - $discountAmount;
    }

    function getTotalAfterDiscountAttribute() : float {
        return $this->unit_cost_after_discount * $this->actual_qty;
    }

    function getUnitAmountAfterTaxAttribute() : float {
        $totalAfterDiscount = $this->unit_cost_after_discount;
        $taxAmount = ($totalAfterDiscount * ($this->tax_percentage ?? 0) / 100);

        return $totalAfterDiscount + $taxAmount;
    }

    function getTotalAfterTaxAttribute() : float {

        return $this->unit_amount_after_tax * $this->actual_qty;
    }

    function getTotalAfterXMarginAttribute() : float {
        $totalAfterTax = $this->unit_amount_after_tax;
        return $totalAfterTax * (1 + (($this->x_margin ?? 0) / 100));
    }
}
