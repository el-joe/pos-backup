<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extract extends Model
{
    use SoftDeletes;

    protected $table = 'extracts';

    protected $fillable = [
        'contract_id',
        'extract_number',
        'type',
        'period_start',
        'period_end',
        'gross_amount',
        'deductions_amount',
        'net_amount',
        'status',
        'approved_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'approved_at' => 'date',
        'paid_at' => 'date',
        'gross_amount' => 'decimal:2',
        'deductions_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function lines()
    {
        return $this->hasMany(ExtractLine::class);
    }

    public function deductions()
    {
        return $this->hasMany(ExtractDeduction::class);
    }

    public function journalEntries()
    {
        return $this->morphMany(JournalEntry::class, 'referenceable');
    }
}
