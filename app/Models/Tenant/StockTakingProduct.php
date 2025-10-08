<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class StockTakingProduct extends Model
{
    protected $fillable = [
        'stock_taking_id',
        'product_id',
        'current_qty',
        'actual_qty',
        'stock_id',
        'returned'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockTaking()
    {
        return $this->belongsTo(StockTaking::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    function getDifferenceAttribute() {
        return $this->actual_qty - $this->current_qty;
    }
}
