<?php

namespace App\Models\Tenant\Contracting;

use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends Model
{
    use SoftDeletes;

    protected $table = 'tenders';

    protected $fillable = [
        'code',
        'name',
        'client_id',
        'submission_date',
        'opening_date',
        'estimated_value',
        'status',
        'scope_of_work',
        'notes',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'opening_date' => 'date',
        'estimated_value' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }

    public function boqs()
    {
        return $this->hasMany(Boq::class);
    }

    public function quotations()
    {
        return $this->hasMany(SupplierQuotation::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }
}
