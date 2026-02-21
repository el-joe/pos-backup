<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'yearly_allowance',
        'is_paid',
    ];

    protected $casts = [
        'yearly_allowance' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['search'] ?? null, fn($q, $search) => $q->where('name', 'like', "%{$search}%"));
    }
}

