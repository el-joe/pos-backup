<?php

namespace App\Models\Tenant;

use App\Models\Tenant\AccountType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class AccountTypeGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','deleted_at'
    ];

    function accountTypes() {
        return $this->hasMany(AccountType::class,'account_type_group_id');
    }
}
