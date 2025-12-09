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
        return $this->belongsTo(Account::class,'account_id')->withTrashed();
    }

    function getRefAttribute(){
        return $this->transaction?->reference_type ? (new $this->transaction?->reference_type)->getTable() : 'N/A';
    }

    function scopeFilter($query, $filters)
    {
        return $query->when(isset($filters['transaction_id']) && $filters['transaction_id'] !== '', function($q) use ($filters) {
                $q->where('transaction_id', $filters['transaction_id']);
            })
            ->when(isset($filters['transaction_type']) && $filters['transaction_type'] !== 'all', function($q) use ($filters) {
                $q->whereHas('transaction', function($q2) use ($filters) {
                    $q2->where('type', $filters['transaction_type']);
                });
            })
            ->when(isset($filters['branch_id']) && $filters['branch_id'] !== 'all', function($q) use ($filters) {
                $q->whereHas('transaction', function($q2) use ($filters) {
                    $q2->where('branch_id', $filters['branch_id']);
                });
            })
            ->when(isset($filters['date']) && $filters['date'] !== '', function($q) use ($filters) {
                $q->whereHas('transaction', function($q2) use ($filters) {
                    $q2->whereDate('date', '>=', $filters['date']);
                });
            });
    }

}
