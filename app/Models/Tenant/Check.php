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
    ];

    protected $casts = [
        'check_date' => 'date',
        'due_date' => 'date',
        'collected_at' => 'datetime',
        'bounced_at' => 'datetime',
        'cleared_at' => 'datetime',
    ];

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
