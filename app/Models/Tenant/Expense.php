<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'branch_id','expense_category_id','amount','expense_date','note','created_by'
    ];
}
