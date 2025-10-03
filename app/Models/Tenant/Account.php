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

    function scopeFilter($q,$filters = []) {
        return $q->when(isset($filters['model_type']) && $filters['model_type'], function($q) use ($filters) {
            $q->where('model_type', $filters['model_type']);
        })->when(isset($filters['model_id']) && $filters['model_id'], function($q) use ($filters) {
            $q->where('model_id', $filters['model_id']);
        });
    }

    static function default($name,$type,$branch_id) {
        return self::firstOrCreate([
            'code' => $name,
            'type' => $type,
            'branch_id' => $branch_id,
        ],[
            'name' => $name,
            'code' => $name,
            'model_type' => Branch::class,
            'model_id' => $branch_id,
            'type' => $type,
            'branch_id' => $branch_id,
            'active' => 1,
        ]);
    }
}
