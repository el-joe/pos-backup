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

    function isBalanced(): bool
    {
        $debit = (float) $this->lines->where('type', 'debit')->sum('amount');
        $credit = (float) $this->lines->where('type', 'credit')->sum('amount');

        return abs($debit - $credit) <= 0.005;
    }

    function scopeUnbalanced($query)
    {
        return $query->whereRaw("(
            select coalesce(sum(case when tl.type = 'debit' then tl.amount else 0 end), 0)
                 - coalesce(sum(case when tl.type = 'credit' then tl.amount else 0 end), 0)
            from transaction_lines tl
            where tl.transaction_id = transactions.id
        ) not between -0.005 and 0.005");
    }
}
