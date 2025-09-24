<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class TransactionLine extends Model
{
    protected $fillable = [
        'transaction_id','account_id','type','amount'
    ];

    function transaction() {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }

    function account() {
        return $this->belongsTo(Account::class,'account_id');
    }
}
