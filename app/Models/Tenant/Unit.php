<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','count','parent_id','active','deleted_at'
    ];


    function children() {
        return $this->hasMany(self::class,'parent_id')->with('children')->withTrashed();
    }

    function parent() {
        return $this->belongsTo(self::class,'parent_id')->with('parent')->withTrashed();
    }

    function isParent() {
        return $this->parent_id == 0;
    }

    function isLastChild() {
        return $this->children->count() == 0;
    }

    function scopeParentOnly($query) {
        return $query->where('parent_id',0);
    }

    function unitQtyIntoProduct() {
        return $this->acualQtyRecursion(1,$this);
    }

    function acualQtyRecursion($acualQty,$unit) {
        if($unit->id != $this->id){
            $acualQty = $acualQty * $this->count;
        }
        if($this->child){
            return $this->child->acualQtyRecursion($acualQty,$this);
        }
        return $acualQty;
    }

    function stock($productId = 0,$branchId = null) {
        $stock = Stock::where('product_id', $productId)
            ->where('unit_id', $this->id)
            ->when($branchId ?? admin()->branch_id, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->first();

        return $stock;
    }

    function scopeFilter($q,$filters) {
        return $q->when($filters['empty_parent_id'] ?? null, function($q) {
            $q->where(function ($q) {
                $q->where('parent_id',0)->orWhereNull('parent_id');
            });
        })->when(isset($filters['parent_id']) && $filters['parent_id'] != 'all', function($q) use ($filters) {
            if($filters['parent_id'] == 0){
                $q->where('parent_id',0);
            }else{
                $q->where('parent_id',$filters['parent_id']);
            }
        })->when($filters['active'] ?? null, function($q) use ($filters) {
            if($filters['active'] != 'all'){
                $q->where('active',$filters['active']);
            }
        })
        ->when($filters['search'] ?? null, function($q) use ($filters) {
            $q->where('name','like','%'.$filters['search'].'%');
        });
    }
}
