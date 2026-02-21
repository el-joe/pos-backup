<?php

namespace App\Models\Tenant;

use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'date','description','reference_type','reference_id','branch_id','note','type','amount'
    ];

    protected $casts = [
        'type' => TransactionTypeEnum::class
    ];

    function reference() {
        return $this->morphTo();
    }

    function lines() {
        return $this->hasMany(TransactionLine::class,'transaction_id');
    }

    function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    function account($type = 'debit'){
        $line = $this->lines()->where('type',$type)->first();
        return $line ? $line->account : null;
    }
}
