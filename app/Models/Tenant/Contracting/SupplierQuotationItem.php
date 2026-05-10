<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;

class SupplierQuotationItem extends Model
{
    protected $table = 'supplier_quotation_items';

    protected $fillable = [
        'supplier_quotation_id',
        'boq_item_id',
        'description',
        'unit',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function quotation()
    {
        return $this->belongsTo(SupplierQuotation::class, 'supplier_quotation_id');
    }

    public function boqItem()
    {
        return $this->belongsTo(BoqItem::class);
    }
}
