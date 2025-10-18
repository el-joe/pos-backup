<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    protected $fillable = [
        'branch_id', 'admin_id', 'opening_balance', 'total_sales', 'total_sale_refunds',
        'total_purchases', 'total_purchase_refunds', 'total_expenses', 'total_expense_refunds',
        'total_deposits', 'total_withdrawals', 'closing_balance', 'opened_at', 'closed_at',
        'status', 'notes'
    ];
}
