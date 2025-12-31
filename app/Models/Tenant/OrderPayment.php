<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'payable_type',
        'payable_id',
        'account_id',
        'amount',
        'refunded',
        'note',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

    function account()
    {
        return $this->belongsTo(Account::class);
    }

    function paymentMethod()
    {
        return $this->hasManyThrough(
            PaymentMethod::class,
            Account::class,
            'id', // Foreign key on Account table...
            'id', // Foreign key on PaymentMethod table...
            'account_id', // Local key on OrderPayment table...
            'payment_method_id' // Local key on Account table...
        )->first();
    }
}
