<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name','active'
    ];

    public function scopeActive($query) {
        return $query->where('active',1);
    }
}
