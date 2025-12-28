<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'tenant_id',
        'payment_method_id',
        'amount',
        'status',
        'request_payload',
        'response_payload',
        'transaction_reference'
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];

    function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
