<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','active','default','name','deleted_at'
    ];


    function expenses() {
        return $this->hasMany(Expense::class,'expense_category_id')->withTrashed();
    }


    function scopeFilter($query, $filters = []) {
        return $query->when($filters['default']??false, function($q) use ($filters) {
            $q->where('default', $filters['default']);
        })->when($filters['name']??false, function($q) use ($filters) {
            $q->where('name', $filters['name']);
        })
        ->when(isset($filters['active']), function($q) use ($filters) {
            if($filters['active'] == 'all') return $q;
            $q->where('active', $filters['active']);
        });
    }
}
