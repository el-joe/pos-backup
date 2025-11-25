<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','parent_id','active','deleted_at','icon'
    ];

    function children() {
        return $this->hasMany(self::class,'parent_id');
    }

    function parent() {
        return $this->belongsTo(self::class,'parent_id');
    }

    function isLastChild() {
        return $this->children->count() == 0;
    }

    function scopeParentOnly($query) {
        return $query->where('parent_id',0);
    }

    function scopeFilter($q,$filters) {
        return $q->when(isset($filters['active']), function($q)  use($filters) {
            if($filters['active'] != 'all'){
                $q->where('active',$filters['active']);
            }
        })
        ->when($filters['parent_id'] ?? null, function($q,$parent_id) {
            if($parent_id != 'all'){
                $q->where('parent_id',$parent_id);
            }
        })
        ->when($filters['empty_parent_id'] ?? null, function($q) {
            $q->where(function ($q) {
                $q->where('parent_id',0)->orWhereNull('parent_id');
            });
        })
        ->when($filters['search'] ?? null, function($q,$search) {
            $q->where('name','like',"%$search%");
        });
    }

    function icon(){
        return $this->morphOne(File::class,'model')->key('icon');
    }
}
