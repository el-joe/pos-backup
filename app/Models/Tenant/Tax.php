<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['name','vat_number','rate'];

    function scopeFilter($q,$filter) {
        return $q->when($filter['search'] ?? null, function($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->when($filter['active'] ?? null, function($query, $active) {
            if ($active !== 'all') {
                $query->where('active', $active);
            }
        });
    }
}
