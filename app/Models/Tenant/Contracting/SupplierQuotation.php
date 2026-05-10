<?php

namespace App\Models\Tenant\Contracting;

use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierQuotation extends Model
{
    use SoftDeletes;

    protected $table = 'supplier_quotations';

    protected $fillable = [
        'code',
        'boq_id',
        'tender_id',
        'supplier_id',
        'quotation_date',
        'valid_until',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function boq()
    {
        return $this->belongsTo(Boq::class);
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(SupplierQuotationItem::class);
    }
}
