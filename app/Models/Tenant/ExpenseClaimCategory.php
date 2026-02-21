<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseClaimCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['search'] ?? null, fn($q, $search) => $q->where('name', 'like', "%{$search}%"));
    }
}

