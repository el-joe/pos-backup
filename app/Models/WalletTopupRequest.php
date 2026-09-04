<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTopupRequest extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_id',
        'amount',
        'payment_method_id',
        'manual',
        'currency_id',
        'currency_code',
        'currency_symbol',
        'conversion_rate',
        'converted_amount',
        'receipt_path',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'amount' => 'float',
        'manual' => 'boolean',
        'conversion_rate' => 'float',
        'converted_amount' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (WalletTopupRequest $request) {
            \Illuminate\Support\Facades\Mail::to(env('ADMIN_EMAIL', 'eljoe1717@gmail.com'))
                ->send(new \App\Mail\AdminSubscriptionRequestMail($request, 'Wallet Top-up Request'));
        });

        static::updated(function (WalletTopupRequest $request) {
            if (!$request->isDirty('status')) {
                return;
            }

            if ($request->status === 'approved') {
                $request->applyToBalance();
                notifyTenantAdmins($request->tenant_id, __('notifications.wallet_topup_approved', [
                    'amount' => number_format($request->amount, 2),
                ]));
            } elseif ($request->status === 'rejected') {
                notifyTenantAdmins($request->tenant_id, __('notifications.wallet_topup_rejected', [
                    'amount' => number_format($request->amount, 2),
                ]));
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function applyToBalance(): void
    {
        $tenant = $this->tenant;
        if (!$tenant) {
            return;
        }

        $tenant->update(['balance' => $tenant->balance + $this->amount]);
    }
}
