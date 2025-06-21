<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name','email','address','phone','active'
    ];

    public function scopeActive($q) {
        return $q->where('active',1);
    }
}
