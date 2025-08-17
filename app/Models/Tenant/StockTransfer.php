<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $fillable = [
        'from_branch_id',
        'to_branch_id',
        'transfer_date',
        'product_id',
        'quantity',
    ];

    // Relationships and other model methods can be defined here

    function fromBranch() {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    function toBranch() {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
