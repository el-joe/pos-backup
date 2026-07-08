<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $table = 'equipment';

    protected $fillable = [
        'code',
        'name',
        'type',
        'asset_tag',
        'hourly_cost_rate',
        'daily_cost_rate',
        'supplier_name',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'hourly_cost_rate' => 'decimal:2',
        'daily_cost_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function logs()
    {
        return $this->hasMany(EquipmentLog::class);
    }
}
