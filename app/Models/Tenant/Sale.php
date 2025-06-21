<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id','branch_id','ref_no','order_date'
    ];

    public function saleVariables() {
        return $this->hasMany(SaleVariable::class);
    }

    public function customer() {
        return $this->belongsTo(Contact::class,'customer_id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
