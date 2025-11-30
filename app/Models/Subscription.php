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

    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    function isActive()
    {
        $startDate = carbon($this->start_date);
        $endDate = carbon($this->end_date);
        $now = now();
        return $this->status === 'paid' && $now->between($startDate, $endDate);
    }

    // if billing_cycle is monthly he can cancel first 3 days from start date , if yearly he can cancel first 14 days
    function canCancel(){
        $startDate = carbon($this->start_date);
        $now = now();

        if ($this->billing_cycle === 'monthly') {
            return $now->diffInDays($startDate) <= 3;
        } elseif ($this->billing_cycle === 'yearly') {
            return $now->diffInDays($startDate) <= 14;
        }

        return false;
    }

    // if billing_cycle is monthly he can renew last 3 days from end date , if yearly he can renew last 14 days
    function canRenew(){
        $endDate = carbon($this->end_date);
        $now = now();

        if ($this->billing_cycle === 'monthly') {
            return $now->diffInDays($endDate) <= 3;
        } elseif ($this->billing_cycle === 'yearly') {
            return $now->diffInDays($endDate) <= 14;
        }

        return false;
    }
}
