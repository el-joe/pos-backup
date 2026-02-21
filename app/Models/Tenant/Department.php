<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'manager_id',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->withTrashed();
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->withTrashed();
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id')->withTrashed();
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            });
    }
}
