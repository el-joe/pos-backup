<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Branch;
use App\Models\Tenant\Contact;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id','branch_id','ref_no','order_date','status','discount_percentage','tax_percentage'
    ];

    const STATUS = ['requested','pending','received'];

    function supplier(){
        return $this->belongsTo(Contact::class,'supplier_id')
            ->where('contacts.type','supplier');
    }

    function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    function purchaseVariables() {
        return $this->hasMany(PurchaseVariable::class,'purchase_id');
    }
}
