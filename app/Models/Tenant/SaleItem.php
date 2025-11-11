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

    function getTotalCostAttribute()  {
        return numFormat($this->unit_cost * $this->actual_qty,3);
    }

    function getTotalAttribute()  {
        return SaleHelper::itemTotal($this);
    }

    function getGrandTotalAttribute()  {
        $total = $this->total;
        $discount = $this->total_discount_amount;
        $taxAmount = $this->total_tax_amount;
        return numFormat($total - $discount + $taxAmount,3);
    }

    function getTotalDiscountAmountAttribute() {
        $sale = $this->sale;
        return SaleHelper::singleDiscountAmount($this, clone $sale->saleItems, $sale->discount_type, $sale->discount_value, $sale->max_discount_amount);
    }

    function getTotalTaxAmountAttribute() {
        $sale = $this->sale;
        return SaleHelper::singleTaxAmount($this, clone $sale->saleItems, $sale->discount_type, $sale->discount_value, $sale->tax_percentage, $sale->max_discount_amount);
    }

    function getRefundedTotalAttribute()  {
        $saleItem = $this;
        $saleItem['qty'] = $this->refunded_qty;
        unset($saleItem['refunded_qty']);
        return SaleHelper::itemTotal($saleItem);
    }

    function getRefundedGrandTotalAttribute()  {
        $total = $this->refunded_total;
        $discount = $this->refunded_total_discount_amount;
        $taxAmount = $this->refunded_total_tax_amount;
        return numFormat($total - $discount + $taxAmount,3);
    }

    function getRefundedTotalDiscountAmountAttribute() {
        $sale = $this->sale;
        return SaleHelper::singleDiscountAmount($this, clone $sale->salesItemsRefunded(), $sale->discount_type, $sale->discount_value, $sale->max_discount_amount);
    }

    function getRefundedTotalTaxAmountAttribute() {
        $sale = $this->sale;
        return SaleHelper::singleTaxAmount($this, clone $sale->salesItemsRefunded(), $sale->discount_type, $sale->discount_value, $sale->tax_percentage, $sale->max_discount_amount);
    }

}
