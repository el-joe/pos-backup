<?php

namespace App\Models\Tenant;

use App\Helpers\SaleHelper;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    // create boot method for created by
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Assuming you have an auth system in place
            if (admin()?->id) {
                $model->created_by = admin()->id;
            }
        });
    }
    protected $fillable = [
        'branch_id',
        'order_type',
        'order_id',
        'reason',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(RefundItem::class);
    }

    public function order()
    {
        return $this->morphTo();
    }

    function getTotalAttribute(){
        return $this->items->sum(function($item){
            $refundable = $item->refundable;

            if ($refundable instanceof SaleItem) {
                $saleOrder = $refundable->sale;
                if (!$saleOrder) {
                    return 0;
                }

                $product = $refundable->toArray();
                $product['qty'] = $item->qty;
                $product['refunded_qty'] = 0;
                $taxPercentage = (int) ($refundable->taxable ?? 0) === 1 ? ($saleOrder->tax_percentage ?? 0) : 0;

                return SaleHelper::singleGrandTotal(
                    $product,
                    $saleOrder->saleItems,
                    $saleOrder->discount_type,
                    $saleOrder->discount_value,
                    $taxPercentage,
                    $saleOrder->max_discount_amount ?? 0,
                );
            }

            return $item->qty * ($refundable?->unit_amount_after_tax ?? 0);
        });
    }
}
