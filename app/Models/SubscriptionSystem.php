<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSystem extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'subscription_id',
        'system_slug',
    ];

    function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
