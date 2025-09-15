<?php

namespace App\Models\Tenant;

use App\Enums\PurchaseStatusEnum;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Contact;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id','branch_id','ref_no','order_date','status'
    ];

    protected $casts = [
        'status' => PurchaseStatusEnum::class,
    ];

    function supplier(){
        return $this->belongsTo(User::class,'supplier_id')
            ->where('users.type','supplier');
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
}
