<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name','email','phone','address','active','type'
    ];

    const TYPE = ['customer','supplier'];

    function scopeSuppliers($q) {
        return $q->whereType('supplier');
    }

    function scopeCustomers($q) {
        return $q->whereType('customer');
    }
}
