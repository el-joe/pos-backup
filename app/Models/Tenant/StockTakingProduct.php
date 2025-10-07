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
        'stock_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
