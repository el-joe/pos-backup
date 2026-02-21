<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAssetExtension extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fixed_asset_id',
        'branch_id',
        'created_by',
        'amount',
        'added_useful_life_months',
        'extension_date',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'added_useful_life_months' => 'integer',
        'extension_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'fixed_asset_id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by')->withTrashed();
    }
}
