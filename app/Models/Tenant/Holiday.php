<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'ar_name',
        'description',
        'date',
        'year',
        'days',
        'is_recurring',
        'branch_id',
        'active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
        'active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'ar' && $this->ar_name ? $this->ar_name : $value;
    }
}
