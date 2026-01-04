<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerCommission extends Model
{
    use SoftDeletes;

    protected $connection = 'central';

    protected $fillable = [
        'partner_id',
        'tenant_id',
        'currency_id',
        'subscription_id',
        'amount',
        'status',
        'commission_date',
        'collected_at',
    ];

    protected $casts = [
        'commission_date' => 'datetime',
        'collected_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
