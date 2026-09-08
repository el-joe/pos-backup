<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ExpenseAmortisationEntry extends Model
{
    protected $fillable = [
        'expense_id',
        'branch_id',
        'transaction_id',
        'period_year',
        'period_month',
        'amount',
    ];

    protected $casts = [
        'period_year' => 'integer',
        'period_month' => 'integer',
        'amount' => 'decimal:2',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id')->withTrashed();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
