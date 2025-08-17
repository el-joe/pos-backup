<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name','description','sku','unit_id','category_id','brand_id','weight','alert_qty','active','tax_id','tax_rate'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $appends = ['image_path'];
    protected $with = ['image'];

    // Relationships

    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id')->with('children');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    function image() {
        return $this->morphOne(File::class, 'model')->where('key', 'image');
    }

    function gallery() {
        return $this->morphMany(File::class, 'model')->where('key', 'gallery');
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

    // Accessors

    function getImagePathAttribute() {
        return $this->image ? $this->image->full_path : null;
    }

    function getGalleryPathAttribute() {
        return $this->gallery ? $this->gallery->pluck('full_path') : null;
    }


    function units() {
        $this->getUnitAndChildUnitRecursion($this->unit,$output);
        return $output;
    }

    function getUnitAndChildUnitRecursion($unit,&$units = []) {
        $units[] = $unit;
        if($unit->child) {
            $this->getUnitAndChildUnitRecursion($unit->child,$units);
        }
    }
}
