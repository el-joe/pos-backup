<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'branch_id','expense_category_id','amount','expense_date','note','created_by','model_type','model_id','tax_percentage'
    ];

    public function category() {
        return $this->belongsTo(ExpenseCategory::class,'expense_category_id')->withTrashed();
    }

    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id')->withTrashed();
    }

    public function model() {
        return $this->morphTo();
    }

    function scopeFilter($query,$filter = []) {
        return $query->when(isset($filter['branch_id']) && $filter['branch_id'], function($q) use ($filter) {
            $q->where('branch_id', $filter['branch_id']);
        })->when(isset($filter['expense_category_id']) && $filter['expense_category_id'], function($q) use ($filter) {
            $q->where('expense_category_id', $filter['expense_category_id']);
        })->when(isset($filter['date_from']) && $filter['date_from'], function($q) use ($filter) {
            $q->whereDate('expense_date', '>=', $filter['date_from']);
        })->when(isset($filter['date_to']) && $filter['date_to'], function($q) use ($filter) {
            $q->whereDate('expense_date', '<=', $filter['date_to']);
        })
        ->when(isset($filter['with_trashed']) && $filter['with_trashed'], function($q) {
            $q->withTrashed();
        })
        ->when(isset($filter['date']) && $filter['date'], function($q) use ($filter) {
            $q->whereDate('expense_date', $filter['date']);
        });
    }

    function getTotalAttribute() {
        return $this->amount + ($this->amount * $this->tax_percentage / 100);
    }
}
