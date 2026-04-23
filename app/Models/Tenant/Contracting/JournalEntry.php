<?php

namespace App\Models\Tenant\Contracting;

use App\Models\Tenant\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $table = 'journal_entries';

    protected $fillable = [
        'reference',
        'referenceable_type',
        'referenceable_id',
        'date',
        'description',
        'status',
        'total_debit',
        'total_credit',
        'posted_by',
        'posted_at',
    ];

    protected $casts = [
        'date' => 'date',
        'posted_at' => 'datetime',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
    ];

    public function referenceable()
    {
        return $this->morphTo();
    }

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(Admin::class, 'posted_by')->withTrashed();
    }
}
