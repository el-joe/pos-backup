<?php

namespace App\Models\Tenant;

use App\Enums\RefundStatusEnum;
use App\Helpers\SaleHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    protected $fillable = [
        'customer_id','branch_id','invoice_number','order_date','created_by',
        'tax_id','tax_percentage','discount_id','discount_type','discount_value',
        'paid_amount','max_discount_amount'
    ];

    // boot method to update discount max value to 0 if null
    protected static function booted()
    {
        static::creating(function ($sale) {
            $sale->created_by = admin()->id ?? null;

            if(!empty($sale->discount_id)){
                $discount = Discount::find($sale->discount_id);
                if($discount) {
                    if($discount->type == 'fixed'){
                        $sale->max_discount_amount = $discount->sales_threshold ?? 0;
                    }else{
                        $sale->max_discount_amount = $discount->max_discount_amount ?? 0;
                    }
                }
            }
        });
    }

    public function saleItems() {
        return $this->hasMany(SaleItem::class);
    }

    public function customer() {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    function transactions() {
        return $this->morphMany(Transaction::class, 'reference');
    }

    static function generateInvoiceNumber() {
        $last = self::latest()->first();
        if($last) {
            return 'INV-'.str_pad((int)str_replace('INV-','',$last->invoice_number) + 1, 6, '0', STR_PAD_LEFT);
        }
        return 'INV-000001';
    }

    function getSubTotalAttribute() : float {
        return $this->saleItems->sum(function($item) {
            $total = SaleHelper::itemTotal($item);

            return $total;
        });
    }


    function getDiscountAmountAttribute() {
        $discount = (clone $this->saleItems)->map(function($item){
            return $item->total_discount_amount;
        })->sum();
        return $discount;

        // return SaleHelper::discountAmount($this->saleItems, $this->discount_type, $this->discount_value, $this->max_discount_amount);
    }

    function getTaxAmountAttribute() {
        $tax = (clone $this->saleItems)->map(function($item){
            return SaleHelper::singleTaxAmount($item, $this->saleItems, $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
        })->sum();

        return $tax;

        // return SaleHelper::taxAmount($this->saleItems, $this->discount_type, $this->discount_value, $this->tax_percentage);
    }

    function getGrandTotalAmountAttribute() {
        $grandTotal = (clone $this->saleItems)->map(function($item){
            return SaleHelper::singleGrandTotal($item, $this->saleItems, $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
        })->sum();

        return $grandTotal;

        // return SaleHelper::grandTotal($this->saleItems, $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
    }

    function getDueAmountAttribute() {
        $dueAmount = $this->grand_total_amount - $this->paid_amount;
        if($dueAmount < .01) $dueAmount = 0;
        return numFormat($dueAmount, 3);
    }

    function scopeFilter($q,$filters = []) {
        return $q->when($filters['branch_id'] ?? null, fn($q,$v)=> $q->where('branch_id',$v))
            ->when($filters['customer_id'] ?? null, fn($q,$v)=> $q->where('customer_id',$v))
            ->when($filters['from_date'] ?? null, fn($q,$v)=> $q->whereDate('order_date','>=',$v))
            ->when($filters['to_date'] ?? null, fn($q,$v)=> $q->whereDate('order_date','<=',$v))
            ->when($filters['search'] ?? null, function($q,$v){
                $q->where(function($q) use ($v) {
                    $q->where('invoice_number','like','%'.$v.'%')
                        ->orWhereHas('customer',fn($q)=> $q->where('name','like','%'.$v.'%'));
                });
            })
            ->when($filters['id'] ?? null, fn($q,$v)=> $q->where('id',$v));
    }

    function getRefundStatusAttribute() {
        $totalItems = $this->saleItems->sum('qty');
        $refundedItems = $this->saleItems->sum('refunded_qty');

        if ($refundedItems === 0) {
            return RefundStatusEnum::NO_REFUND;
        } elseif ($refundedItems < $totalItems) {
            return RefundStatusEnum::PARTIAL_REFUND;
        } else {
            return RefundStatusEnum::FULL_REFUND;
        }

    }
}
