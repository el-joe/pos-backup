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
        'status'
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
}
