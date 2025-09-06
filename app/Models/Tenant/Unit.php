<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name','count','parent_id','active'
    ];


    function children() {
        return $this->hasMany(self::class,'parent_id')->with('children');
    }

    function parent() {
        return $this->belongsTo(self::class,'parent_id')->with('parent');
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

    function scopeFilter($q,$filters) {
        return $q->when($filters['empty_parent_id'] ?? null, function($q,$parent_id) {
            $q->where(function ($q) use ($parent_id) {
                $q->where('parent_id',$parent_id)->orWhereNull('parent_id');
            });
        });
    }
}
