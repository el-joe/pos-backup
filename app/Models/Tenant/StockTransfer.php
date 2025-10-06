<?php

namespace App\Models\Tenant;

use App\Enums\StockTransferStatusEnum;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $fillable = [
        'from_branch_id',
        'to_branch_id',
        'transfer_date',
        'ref_no',
        'status',
        'expense_paid_branch_id',
    ];

    protected $casts = [
        'status' => StockTransferStatusEnum::class
    ];

    // Relationships and other model methods can be defined here

    function fromBranch() {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    function toBranch() {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    function items() {
        return $this->hasMany(StockTransferItem::class, 'stock_transfer_id');
    }

    function expenses() {
        return $this->morphMany(Expense::class, 'model');
    }

    function scopeFilter($q,$filters = []) {
        return $q->when($filters['status'] ?? null, function($q,$status) {
            $q->where('status',$status);
        })->when($filters['from_date'] ?? null, function($q,$fromDate) {
            $q->whereDate('transfer_date','>=',$fromDate);
        })->when($filters['to_date'] ?? null, function($q,$toDate) {
            $q->whereDate('transfer_date','<=',$toDate);
        })->when($filters['branch_id'] ?? null, function($q,$branchId) {
            $q->where(function($q) use ($branchId) {
                $q->where('from_branch_id',$branchId)
                  ->orWhere('to_branch_id',$branchId);
            });
        })->when($filters['ref_no'] ?? null, function($q,$refNo) {
            $q->where('ref_no','like',"%$refNo%");
        })->when($filters['search'] ?? null, function($q,$search) {
            $q->where('ref_no','like',"%$search%");
        })->when($filters['id'] ?? null, function($q,$id) {
            $q->where('id',$id);
        });
    }
}
