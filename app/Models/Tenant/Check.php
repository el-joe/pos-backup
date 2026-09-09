<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Check extends Model
{
    use SoftDeletes;

    protected $table = 'checks';

    protected $fillable = [
        'branch_id',
        'direction',
        'status',
        'payable_type',
        'payable_id',
        'order_payment_id',
        'customer_id',
        'supplier_id',
        'amount',
        'bank_charge',
        'check_number',
        'bank_name',
        'check_date',
        'due_date',
        'note',
        'collected_account_id',
        'cleared_account_id',
        'collected_at',
        'bounced_at',
        'cleared_at',
        'represented_at',
        'replaced_by_check_id',
    ];

    protected $casts = [
        'check_date' => 'date',
        'due_date' => 'date',
        'collected_at' => 'datetime',
        'bounced_at' => 'datetime',
        'cleared_at' => 'datetime',
        'represented_at' => 'datetime',
    ];

    /**
     * Repair guard added by prompt 09 (defect #14): a hard-deleted check row leaves GL
     * entries referencing it stranded (transaction_lines whose transaction is dated against
     * a check that no longer exists). Soft-delete is fine (SoftDeletes trait); force-delete
     * is blocked outright — reverse the associated transaction(s) via
     * tenant:repair-financial-data --step=14 first, then soft-delete only.
     */
    protected static function booted(): void
    {
        static::forceDeleting(function () {
            throw new \RuntimeException('Hard-deleting a Check is blocked (prompt 09 guard) — soft-delete instead, and reverse any GL entries referencing it first.');
        });

        static::deleting(function (self $check) {
            if ($check->isForceDeleting()) {
                return;
            }
            if (!in_array($check->status, [\App\Enums\CheckStatusEnum::ISSUED->value, \App\Enums\CheckStatusEnum::UNDER_COLLECTION->value], true)) {
                throw new \RuntimeException('Only a check still under collection/issued (no posted clearing entry) can be deleted.');
            }
        });

        static::creating(function (self $check) {
            if ((float)($check->amount ?? 0) <= 0) {
                throw new \RuntimeException('Check amount must be greater than zero.');
            }
            if (!$check->check_number) {
                throw new \RuntimeException('Check number is required.');
            }
            if (!$check->bank_name) {
                throw new \RuntimeException('Bank name is required.');
            }
            if (!$check->due_date) {
                throw new \RuntimeException('Due date is required.');
            }
        });
    }

    public function replacedByCheck()
    {
        return $this->belongsTo(self::class, 'replaced_by_check_id');
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function orderPayment()
    {
        return $this->belongsTo(OrderPayment::class, 'order_payment_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id')->withTrashed();
    }

    public function collectedAccount()
    {
        return $this->belongsTo(Account::class, 'collected_account_id')->withTrashed();
    }

    public function clearedAccount()
    {
        return $this->belongsTo(Account::class, 'cleared_account_id')->withTrashed();
    }
}
