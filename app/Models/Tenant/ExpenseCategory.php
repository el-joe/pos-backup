<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','active','default','name'
    ];


    function expenses() {
        return $this->hasMany(Expense::class,'expense_category_id');
    }


    function scopeFilter($query, $filters = []) {
        return $query->when($filters['default']??false, function($q) use ($filters) {
            $q->where('default', $filters['default']);
        })->when($filters['name']??false, function($q) use ($filters) {
            $q->where('name', $filters['name']);
        });
    }
}
