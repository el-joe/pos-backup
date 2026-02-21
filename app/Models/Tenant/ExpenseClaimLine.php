<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ExpenseClaimLine extends Model
{
    protected $fillable = [
        'expense_claim_id',
        'category_id',
        'amount',
        'description',
        'receipt_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function claim()
    {
        return $this->belongsTo(ExpenseClaim::class, 'expense_claim_id');
    }

    public function category()
    {
        return $this->belongsTo(ExpenseClaimCategory::class, 'category_id')->withTrashed();
    }
}
