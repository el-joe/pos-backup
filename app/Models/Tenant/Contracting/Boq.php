<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Boq extends Model
{
    use SoftDeletes;

    protected $table = 'boqs';

    protected $fillable = [
        'code',
        'tender_id',
        'project_id',
        'type',
        'title',
        'notes',
        'total_value',
    ];

    protected $casts = [
        'total_value' => 'decimal:2',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(BoqItem::class);
    }

    public function quotations()
    {
        return $this->hasMany(SupplierQuotation::class);
    }
}
