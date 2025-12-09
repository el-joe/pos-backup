<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class StockTransferItem extends Model
{
    protected $fillable = [
        'stock_transfer_id',
        'product_id',
        'unit_id',
        'qty',
        'unit_cost',
        'sell_price',
        'update_prices'
    ];

    // Relationships

    function stockTransfer() {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id')->withTrashed();
    }

    function product() {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    function unit() {
        return $this->belongsTo(Unit::class, 'unit_id')->withTrashed();
    }
}
