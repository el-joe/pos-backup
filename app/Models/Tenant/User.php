<?php

namespace App\Models\Tenant;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'active',
        'type',
        'deleted_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'type' => UserTypeEnum::class,
    ];

    function accounts() {
        return $this->morphMany(Account::class, 'model');
    }

    function scopeFilter($q,$filters = []) {
        return $q->when($filters['type'] ?? null, fn($q,$type) => $q->where('type',$type))
            ->when(isset($filters['active']), fn($q,$active) => $q->where('active',$filters['active']))
            ->when($filters['email'] ?? null, fn($q,$email) => $q->where('email',$email))
            ->when($filters['phone'] ?? null, fn($q,$phone) => $q->where('phone',$phone))
            ->when(isset($filters['is_deleted']) && $filters['is_deleted'] == true, fn($q) => $q->onlyTrashed());
    }
}
