<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name','parent_id','active'
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

}
