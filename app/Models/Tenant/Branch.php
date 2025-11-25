<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','email','address','phone','active','deleted_at','website','tax_id'
    ];

    function tax() {
        return $this->belongsTo(Tax::class);
    }

    function sales() {
        return $this->hasMany(Sale::class,'branch_id');
    }

    public function scopeActive($q) {
        return $q->where('active',1);
    }

    function scopeFilter($q,$filters) {
        return $q->when(isset($filters['active']), function($q)use($filters) {
            if($filters['active'] !== 'all'){
                $q->where('active',$filters['active']);
            }
        })
        ->when($filters['search'] ?? false, function($q,$search) {
            $q->whereAny(['name','phone','email','address'],'like',"%$search%");
        });
    }
}
