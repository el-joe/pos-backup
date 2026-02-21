<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'department_id',
        'base_salary_range',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q->when($filters['search'] ?? null, function ($q, $search) {
            $q->where('title', 'like', "%{$search}%");
        });
    }
}
