<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ProductVariable extends Model
{
    protected $fillable = ['product_id', 'name', 'sku', 'purchase_price_ex_tax', 'purchase_price_inc_tax', 'x_margin', 'sell_price_ex_tax', 'sell_price_inc_tax'];

    protected $appends = ['image_path'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    function image() {
        return $this->morphOne(File::class, 'model')->where('key','image');
    }

    function getImagePathAttribute() {
        return $this->image->full_path??null;
    }

    function stoke() {
        return $this->hasMany(Stoke::class,'product_variable_id');
    }

    // Accessors

    function getStokeTotalQtyAttribute() {

        return $this->stoke->map(function ($s) {
            return $this->productUnitRecursion($s->unit,$s->qty,true);
        })->sum();
    }

    function lastUnitChild() {
        return $this->productUnitRecursionData($this->product->unit);
    }

    function productUnitRecursion($productUnit,$qty,$current = false) {
        $unit = $productUnit->child;
        $unitQty = (float)$productUnit->count;
        $totalQty = $unitQty * $qty;
        if($current) {
            $totalQty = $qty;
        }
        if($unit) {
            return $this->productUnitRecursion($unit,$totalQty);
        }
        return $totalQty;
    }

    function productUnitRecursionData($productUnit) {
        $unit = $productUnit->child;
        if($unit->child) {
            return $this->productUnitRecursionData($unit->child);
        }
        return $unit;
    }
}
