<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id','branch_id','ref_no','order_date'
    ];

    public function saleItems() {
        return $this->hasMany(SaleItem::class);
    }

    public function customer() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
