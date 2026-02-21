<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'department_id',
        'manager_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id')->withTrashed();
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q->when($filters['search'] ?? null, function ($q, $search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }
}
