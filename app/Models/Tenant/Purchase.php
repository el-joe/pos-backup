<?php

namespace App\Models\Tenant;

use App\Enums\PurchaseStatusEnum;
use App\Helpers\PurchaseHelper;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Contact;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id','branch_id','ref_no','order_date','paid_amount','status',
        'discount_type' , 'discount_value' , 'tax_id' , 'tax_percentage'
    ];

    protected $casts = [
        'status' => PurchaseStatusEnum::class,
    ];

    function supplier(){
        return $this->belongsTo(User::class,'supplier_id')
            ->where('users.type','supplier');
    }

    function expenses() {
        return $this->morphMany(Expense::class, 'model');
    }

    function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    function purchaseItems() {
        return $this->hasMany(PurchaseItem::class,'purchase_id');
    }

    function transactions() {
        return $this->morphMany(Transaction::class,'reference');
    }

    function getItemsTotalAmountAttribute() {
        return $this->purchaseItems->sum(fn($q)=>$q->total_after_tax);
    }

    function getTotalAmountAttribute() {
        $totalItems = $this->items_total_amount;
        $subTotal = PurchaseHelper::calcSubTotal($totalItems, $this->expenses_total_amount);
        $discountAmount = PurchaseHelper::calcDiscount($subTotal,$this->discount_type,$this->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($subTotal,$discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount,$this->tax_percentage);

        return PurchaseHelper::calcGrandTotal($totalAfterDiscount,$taxAmount);
    }

    function getExpensesTotalAmountAttribute() {
        return $this->expenses->sum('amount');
    }

    function getDueAmountAttribute() {
        return $this->total_amount - $this->paid_amount;
    }

    function getRefundedStatusAttribute() {
        $refundedQty = $this->purchaseItems->sum('refunded_qty');
        $totalQty = $this->purchaseItems->sum('qty');
        if($refundedQty <= 0) {
            return 'not_refunded';
        } elseif($refundedQty > 0 && $refundedQty < $totalQty) {
            return 'partial_refunded';
        } elseif($refundedQty == $totalQty) {
            return 'full_refunded';
        }
    }

    function scopeFilter($q, array $filters = []) {
        return $q->when($filters['id'] ?? false , fn($q,$id) => $q->where('id',$id) );
    }
}
