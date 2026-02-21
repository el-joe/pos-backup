<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','active','deleted_at'
    ];

    public function scopeActive($query) {
        return $query->where('active',1);
    }

    function scopeFilter($q,$filters = []) {
        return $q->when(isset($filters['active']), function($q) use ($filters) {
            if($filters['active'] != 'all'){
                $q->where('active',$filters['active']);
            }
        })
        ->when($filters['search'] ?? false, function($q) use ($filters) {
            $q->where('name','like','%'.$filters['search'].'%');
        });
    }

}
