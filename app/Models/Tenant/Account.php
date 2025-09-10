<?php

namespace App\Models\Tenant;

use App\Enums\AccountTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'branch_id',
        'model_type',
        'model_id',
        'payment_method_id',
        'active',
        'deleted_at',
    ];

    protected $casts = [
        'type' => AccountTypeEnum::class,
    ];

    function branch() {
        return $this->belongsTo(Branch::class);
    }

    function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class);
    }
}
