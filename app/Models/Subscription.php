<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'plan_details',
        'price',
        'systems_allowed',
        'start_date',
        'end_date',
        'status',
        'payment_gateway',
        'payment_details',
        'payment_callback_details',
        'billing_cycle',
    ];

    protected $casts = [
        'plan_details' => 'array',
        'systems_allowed' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'payment_details' => 'array',
        'payment_callback_details' => 'array',
        'billing_cycle' => 'string',
    ];
}
