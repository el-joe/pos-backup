<?php

namespace App\Models\Tenant;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasRoles;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'type','active','branch_id'
    ];

    const TYPE = [
        'super_admin', 'admin'
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    function image()
    {
        return $this->morphOne(File::class, 'model')->where('key','image');
    }


    function branch() {
        return $this->belongsTo(Branch::class);
    }


    function getImagePathAttribute() {
        return $this->image?->full_path ?? asset('adminBoard/plugins/images/defaultUser.svg');
    }

    function scopeFilter($query,$filters = []) {
        return $query->when(isset($filters['type']), function($q) use ($filters) {
            $q->where('type',$filters['type']);
        })->when(isset($filters['active']), function($q) use ($filters) {
            $q->where('active',$filters['active']);
        })
        ->when(isset($filters['search']), function($q) use ($filters) {
            $q->whereAny(['name','phone','email'], 'like', '%'.$filters['search'].'%');
        })
        ->when(isset($filters['branch_id']), function($q) use ($filters) {
            if($filters['branch_id'] === 'all') {
                return $q;
            }
            $q->where('branch_id',$filters['branch_id']);
        });
    }
}
