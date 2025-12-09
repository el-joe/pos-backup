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

    function scopeFilter($query,$filters = []) {
        return $query->when($filters['opened_at'] ?? null, function($q,$openedAt) {
            $q->whereDate('opened_at', $openedAt);
        })->when($filters['closed_at'] ?? null, function($q,$closedAt) {
            $q->whereDate('closed_at', $closedAt);
        })
        ->when($filters['not_closed'] ?? null, function($q,$notClosed) {
            if($notClosed) {
                $q->whereNull('closed_at');
            }
        })
        ->when($filters['status'] ?? null, function($q,$status) {
            $q->where('status', $status);
        })->when($filters['admin_id'] ?? null, function($q,$adminId) {
            $q->where('admin_id', $adminId);
        })->when($filters['branch_id'] ?? null, function($q,$branchId) {
            $q->where('branch_id', $branchId);
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }
}
