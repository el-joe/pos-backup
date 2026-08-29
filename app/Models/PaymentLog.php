<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'payment_method_id',
        'gateway',
        'event',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
