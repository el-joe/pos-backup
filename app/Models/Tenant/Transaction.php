<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'date','description','reference_type','reference_id','branch_id','note'
    ];

    function reference() {
        return $this->morphTo();
    }

    function lines() {
        return $this->hasMany(TransactionLine::class,'transaction_id');
    }
}
