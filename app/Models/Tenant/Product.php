<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','description','sku','code','unit_id','branch_id','category_id','brand_id','weight','alert_qty','active','taxable','deleted_at'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $appends = ['image_path','gallery_path'];
    protected $with = ['image','gallery'];

    // Relationships

    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id')->withTrashed()->with('children');
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    function stocks() {
        return $this->hasMany(Stock::class,'product_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }

    function image() {
        return $this->morphOne(File::class, 'model')->where('key', 'image');
    }

    function gallery() {
        return $this->morphMany(File::class, 'model')->where('key', 'gallery');
    }

    function saleItems() {
        return $this->hasMany(SaleItem::class,'product_id');
    }


    // Scopes

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    function scopeFilter($query,$filter = []) {
        return $query->when($filter['has_stocks'] ?? null, function($q) {
            $q->whereHas('stocks', function($q2) {
                $q2->where('qty','>',0);
            });
        })->when(isset($filter['active']), function($q) use ($filter) {
            if($filter['active'] != 'all'){
                $q->where('active', $filter['active']);
            }
        })->when($filter['inactive'] ?? null, function($q) {
            $q->where('active', 0);
        })->when($filter['category_id'] ?? null, function($q,$categoryId) {
            if($categoryId != 'all'){
                $q->where('category_id', $categoryId);
            }
        })->when($filter['brand_id'] ?? null, function($q,$brandId) {
            if($brandId != 'all'){
                $q->where('brand_id', $brandId);
            }
        })->when($filter['branch_id'] ?? null, function($q,$branchId) {
            if($branchId != 'all'){
                $q->whereHas('stocks', function($q2) use ($branchId) {
                    $q2->where('branch_id', $branchId);
                });
            }
        })->when($filter['search'] ?? null, function($q,$term) {
            $q->where(function($q2) use ($term) {
                $q2->whereAny(['name','sku','code'],'like','%'.$term.'%');
            });
        });
    }


    // Accessors

    function getImagePathAttribute() {
        return $this->image ? $this->image->full_path : null;
    }

    function getGalleryPathAttribute() {
        return $this->gallery ? $this->gallery->pluck('full_path') : null;
    }

    public function units()
    {
        $unit = $this->unit;
        $units = collect();
        if ($unit) {
            $units->push($unit);
            $units = $units->merge($this->collectChildrenRecursively($unit));
        }
        return $units;
    }

    protected function collectChildrenRecursively($unit)
    {
        $all = collect();
        if ($unit->children && $unit->children->count()) {
            foreach ($unit->children as $child) {
                $all->push($child);
                $all = $all->merge($this->collectChildrenRecursively($child));
            }
        }
        return $all;
    }

    function getBranchStockAttribute() {
        $branchId = $this->branch_id ?? branch()?->id ?? null;
        $unit = $this->unit;
        if(!$unit) return 0;
        $numbers = $this->childUnitFromParent($unit,$branchId);
        return round($numbers->sum(),3);
    }

    function branchStock($unitId,$branchId) : float {
        $unit = Unit::find($unitId);
        if(!$unit) return 0;
        $numbers = $this->childUnitFromParent($unit,$branchId);
        return round($numbers->sum(),3);
    }


    function getAllStockAttribute() {
        $unit = $this->unit;
        if(!$unit) return 0;
        $numbers = $this->childUnitFromParent($unit,null);
        return round($numbers->sum(),3);
    }


    function childUnitFromParent($unit, $branchId = null) {
        $all = collect();

        // Get stock for this unit
        $stock = $this->stocks()
            ->when($branchId, fn($query) => $query->where('branch_id', $branchId))
            ->where('unit_id', $unit->id)
            ->first();

        // Only calculate if stock exists
        if ($stock) {
            // Get the conversion factor from parent unit to this unit
            $conversionFactor = $this->getUnitConversionFactor($unit);

            if ($conversionFactor > 0) {
                // Calculate equivalent quantity in parent unit terms
                $equivalentQty = $stock->qty / $conversionFactor;
                $all->push($equivalentQty);
            }
        }

        // Process children recursively
        if ($unit->children && $unit->children->count()) {
            foreach ($unit->children as $child) {
                $all = $all->merge($this->childUnitFromParent($child, $branchId));
            }
        }

        return $all;
    }

    function getUnitConversionFactor($unit) {
        $factor = 1;
        $currentUnit = $unit;

        // Traverse up to the root unit, multiplying counts
        while ($currentUnit->parent) {
            $factor *= $currentUnit->count;
            $currentUnit = $currentUnit->parent;
        }

        return $factor;
    }

    function parentUnitFromChild($child, $multiplier = 1) {
        // This method should return the conversion factor, not stock
        if (!$child->parent) {
            return $multiplier;
        }

        $newMultiplier = $multiplier * $child->count;
        return $this->parentUnitFromChild($child->parent, $newMultiplier);
    }

    function getStockSellPriceAttribute() {
        $branchId = $this->branch_id ?? Branch::active()->first()?->id ?? null;
        $stock = $this->stocks()->when($branchId, fn($query) => $query->where('branch_id', $branchId))->first();
        return $stock ? number_format($stock->sell_price, 2) : 0;
    }

    function stockSellPrice($branchId = null) {
        $branchId = $branchId ?? Branch::active()->first()?->id ?? null;
        $stock = $this->stocks()->when($branchId, fn($query) => $query->where('branch_id', $branchId))->first();
        return $stock ? number_format($stock->sell_price, 2) : 0;
    }

    function quantityAlert($branchId){
        $stock = $this->branchStock($this->unit_id,$branchId);
        $alertQty = $this->alert_qty ?? 0;
        if($stock == 0){
            return 'out_of_stock';
        }elseif($stock <= $alertQty){
            return 'low_stock';
        }

        return null;
    }
}
