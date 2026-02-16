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
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id')->withTrashed();
    }

    function scopeFilter($q,$filters = []) {

        return $q
            ->when(isset($filters['model_type']) && $filters['model_type'], function($q) use ($filters) {
                $q->where('model_type', $filters['model_type']);
            })
            ->when(isset($filters['model_id']) && $filters['model_id'], function($q) use ($filters) {
                $q->where('model_id', $filters['model_id']);
            })
            ->when(array_key_exists('branch_id', $filters) && $filters['branch_id'] !== null && $filters['branch_id'] !== '', function($q) use ($filters) {
                $branchId = $filters['branch_id'];
                if (is_array($branchId)) {
                    $q->whereIn('branch_id', $branchId);
                } else {
                    $q->where('branch_id', $branchId);
                }
            })
            ->when(array_key_exists('type', $filters) && $filters['type'] !== null && $filters['type'] !== '', function($q) use ($filters) {
                $type = $filters['type'];
                if (is_array($type)) {
                    $q->whereIn('type', $type);
                } else {
                    $q->where('type', $type);
                }
            })
            ->when(array_key_exists('active', $filters) && $filters['active'] !== null && $filters['active'] !== '' && $filters['active'] !== 'all', function($q) use ($filters) {
                $q->where('active', $filters['active']);
            })
            ->when(array_key_exists('payment_method_id', $filters) && $filters['payment_method_id'] !== null && $filters['payment_method_id'] !== '', function($q) use ($filters) {
                $q->where('payment_method_id', $filters['payment_method_id']);
            })
            ->when(array_key_exists('has_payment_method', $filters), function($q) use ($filters) {
                if ($filters['has_payment_method']) {
                    $q->whereNotNull('payment_method_id');
                }
            });
    }

    static function default($name,$type,$branch_id = null) {
        $method = PaymentMethod::whereSlug('cash')
            ->where(function ($q) use ($branch_id) {
                $q->where('branch_id',$branch_id)->orWhereNull('branch_id');
            })
            ->first();

        $codeFromName = strtolower(str_replace(' ','_',$name));

        $code = self::generateCodeRecursive($codeFromName);

        $account = self::withTrashed()->firstOrCreate([
            'code' => $codeFromName,
            'type' => $type,
            'branch_id' => $branch_id,
            'payment_method_id' => $method?->id
        ],[
            'name' => $name,
            'code' => $code,
            'model_type' => Branch::class,
            'model_id' => $branch_id,
            'type' => $type,
            'branch_id' => $branch_id,
            'payment_method_id' => $method?->id,
            'active' => 1,
        ]);

        if ($account->trashed()) {
            $account->restore();
        }

        return $account;
    }

    static function defaultForPaymentMethodSlug($name, $type, $branch_id = null, string $paymentMethodSlug = 'cash') {
        $method = PaymentMethod::whereSlug($paymentMethodSlug)
            ->where(function ($q) use ($branch_id) {
                $q->where('branch_id',$branch_id)->orWhereNull('branch_id');
            })
            ->first();

        $codeFromName = strtolower(str_replace(' ','_',$name));
        $code = self::generateCodeRecursive($codeFromName);

        $account = self::withTrashed()->firstOrCreate([
            'code' => $codeFromName,
            'type' => $type,
            'branch_id' => $branch_id,
            'payment_method_id' => $method?->id,
        ],[
            'name' => $name,
            'code' => $code,
            'model_type' => Branch::class,
            'model_id' => $branch_id,
            'type' => $type,
            'branch_id' => $branch_id,
            'payment_method_id' => $method?->id,
            'active' => 1,
        ]);

        if ($account->trashed()) {
            $account->restore();
        }

        return $account;
    }

    static function generateCodeRecursive($code) {
        $exists = self::where('code',$code)->exists();
        if($exists) {
            $newCode = $code .'-'. rand(10,99);
            return self::generateCodeRecursive($newCode);
        }
        return $code;
    }
}
