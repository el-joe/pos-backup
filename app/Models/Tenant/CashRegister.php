<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashRegister extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id', 'admin_id', 'opening_balance', 'total_sales', 'total_sale_refunds',
        'total_purchases', 'total_purchase_refunds', 'total_expenses', 'total_expense_refunds',
        'total_deposits', 'total_withdrawals', 'closing_balance', 'expected_closing_balance',
        'discrepancy', 'discrepancy_reason', 'discrepancy_approved_by', 'discrepancy_approved_at',
        'opened_at', 'closed_at', 'status', 'notes'
    ];

    protected $casts = [
        'opening_balance' => 'float',
        'total_sales' => 'float',
        'total_sale_refunds' => 'float',
        'total_purchases' => 'float',
        'total_purchase_refunds' => 'float',
        'total_expenses' => 'float',
        'total_expense_refunds' => 'float',
        'total_deposits' => 'float',
        'total_withdrawals' => 'float',
        'closing_balance' => 'float',
        'expected_closing_balance' => 'float',
        'discrepancy' => 'float',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'discrepancy_approved_at' => 'datetime',
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

    function scopePendingDiscrepancy($query) {
        return $query->where('status', 'closed')
            ->whereNull('discrepancy_approved_at')
            ->whereNotNull('discrepancy')
            ->whereRaw('ABS(discrepancy) > 0.005');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }

    public function discrepancyApprovedBy()
    {
        return $this->belongsTo(Admin::class, 'discrepancy_approved_by')->withTrashed();
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }

    public function getCalculatedClosingBalanceAttribute(): float
    {
        return round(
            (float) $this->opening_balance
            + (float) $this->total_sales
            + (float) $this->total_purchase_refunds
            + (float) $this->total_expense_refunds
            + (float) $this->total_deposits
            - (float) $this->total_sale_refunds
            - (float) $this->total_purchases
            - (float) $this->total_expenses
            - (float) $this->total_withdrawals,
            2
        );
    }
}
