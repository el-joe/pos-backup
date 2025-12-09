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

        return $q->when(isset($filters['model_type']) && $filters['model_type'], function($q) use ($filters) {
            $q->where('model_type', $filters['model_type']);
        })->when(isset($filters['model_id']) && $filters['model_id'], function($q) use ($filters) {
            $q->where('model_id', $filters['model_id']);
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

        return self::firstOrCreate([
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
