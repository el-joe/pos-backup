<?php

namespace App\Models\Tenant;

use App\Enums\AccountTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','active','default','ar_name','deleted_at','parent_id' , 'key'
    ];

    protected $casts = [
        'active' => 'boolean',
        'default' => 'boolean',
        'key' => AccountTypeEnum::class
    ];

    function expenses() {
        return $this->hasMany(Expense::class,'expense_category_id')->withTrashed();
    }

    function children() {
        return $this->hasMany(ExpenseCategory::class,'parent_id');
    }

    function parent() {
        return $this->belongsTo(ExpenseCategory::class,'parent_id');
    }

    function scopeFilter($query, $filters = []) {
        return $query->when($filters['default']??false, function($q) use ($filters) {
            $q->where('default', $filters['default']);
        })->when($filters['name']??false, function($q) use ($filters) {
            $q->where('name', $filters['name']);
        })
        ->when($filters['key'] ?? false, function($q) use ($filters) {
            $q->where('key', $filters['key']);
        })
        ->when(isset($filters['active']), function($q) use ($filters) {
            if($filters['active'] == 'all') return $q;
            $q->where('active', $filters['active']);
        })
        ->when($filters['parent_only']??false, function($q) use ($filters) {
            $q->whereNull('parent_id');
        });
    }
}
