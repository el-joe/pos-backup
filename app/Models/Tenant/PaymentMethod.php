<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'active','slug','branch_id', 'deleted_at'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    function scopeFilter($q,$filters) {
        return $q->when(isset($filters['active']), function($q) use ($filters) {
            if($filters['active'] == 'all') return $q;
            $q->where('active',$filters['active']);
        })->when(isset($filters['branch_id']), function($q) use ($filters) {
            $q->where(fn($qq)=>$qq->where('branch_id',$filters['branch_id'])->orWhereNull('branch_id'));
        })->when(isset($filters['slug']), function($q) use ($filters) {
            $q->where('slug',$filters['slug']);
        })
        ->when($filters['name'] ?? false, function($q) use ($filters) {
            $q->where('name','like','%'.$filters['name'].'%');
        });
    }
}
