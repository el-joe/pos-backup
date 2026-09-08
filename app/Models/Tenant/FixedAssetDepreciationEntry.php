<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class FixedAssetDepreciationEntry extends Model
{
    protected $fillable = [
        'fixed_asset_id',
        'branch_id',
        'transaction_id',
        'period_year',
        'period_month',
        'amount',
        'accumulated_depreciation_after',
    ];

    protected $casts = [
        'period_year' => 'integer',
        'period_month' => 'integer',
        'amount' => 'decimal:2',
        'accumulated_depreciation_after' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'fixed_asset_id')->withTrashed();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
