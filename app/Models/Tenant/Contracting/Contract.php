<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $table = 'contracts';

    protected $fillable = [
        'code',
        'project_id',
        'party_type',
        'party_id',
        'type',
        'start_date',
        'end_date',
        'total_amount',
        'retention_percentage',
        'advance_payment_amount',
        'taxes_percentage',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'retention_percentage' => 'decimal:2',
        'advance_payment_amount' => 'decimal:2',
        'taxes_percentage' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function party()
    {
        return $this->morphTo();
    }

    public function extracts()
    {
        return $this->hasMany(Extract::class);
    }
}
