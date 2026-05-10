<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;

class ExtractDeduction extends Model
{
    protected $table = 'extract_deductions';

    protected $fillable = [
        'extract_id',
        'account_id',
        'type',
        'amount',
        'percentage',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    public function extract()
    {
        return $this->belongsTo(Extract::class);
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }
}
