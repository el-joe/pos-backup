<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    protected $table = 'contracting_purchase_request_items';

    protected $fillable = [
        'purchase_request_id',
        'item_id',
        'description',
        'unit',
        'quantity',
        'estimated_unit_price',
        'estimated_total',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'estimated_unit_price' => 'decimal:2',
        'estimated_total' => 'decimal:2',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function item()
    {
        return $this->belongsTo(ConstructionItem::class, 'item_id');
    }
}
