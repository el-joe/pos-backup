<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class TransactionLine extends Model
{
    protected $fillable = [
        'transaction_id','account_id','type','amount','created_by'
    ];

    static function boot() : void {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = admin()->id;
        });
    }

    function transaction() {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }

    function account() {
        return $this->belongsTo(Account::class,'account_id');
    }

    function getRefAttribute(){
        return $this->transaction?->reference_type ? (new $this->transaction?->reference_type)->getTable() : 'N/A';
    }
}
