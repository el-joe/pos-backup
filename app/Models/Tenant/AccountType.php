<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','account_type_group_id','parent_id'
    ];

    function group() {
        return $this->belongsTo(AccountTypeGroup::class,'account_type_group_id');
    }

    function children() {
        return $this->hasMany(self::class,'parent_id')->with('children');
    }

    function parent() {
        return $this->belongsTo(self::class,'parent_id')->with('parent');
    }

    function isLastChild() {
        return $this->children->count() == 0;
    }

    function accounts() {
        return $this->hasMany(Account::class,'account_type_id');
    }
}
