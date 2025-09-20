<?php

namespace App\Models\Tenant;

use App\Enums\PurchaseStatusEnum;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Contact;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id','branch_id','ref_no','order_date','paid_amount','status'
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

    function transaction() {
        return $this->morphOne(Transaction::class,'reference');
    }

    function getTotalAmountAttribute() {
        return $this->transaction?->total_amount ?? 0;
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
}
