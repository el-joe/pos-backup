<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'branch_id','expense_category_id','amount','expense_date','note','created_by','model_type','model_id'
    ];

    public function category() {
        return $this->belongsTo(ExpenseCategory::class,'expense_category_id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function model() {
        return $this->morphTo();
    }
}
