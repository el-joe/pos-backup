<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;

class ExtractLine extends Model
{
    protected $table = 'extract_lines';

    protected $fillable = [
        'extract_id',
        'boq_item_id',
        'previous_quantity',
        'current_quantity',
        'total_quantity_to_date',
        'unit_price',
        'total_line_amount',
    ];

    protected $casts = [
        'previous_quantity' => 'decimal:3',
        'current_quantity' => 'decimal:3',
        'total_quantity_to_date' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'total_line_amount' => 'decimal:2',
    ];

    public function extract()
    {
        return $this->belongsTo(Extract::class);
    }

    public function boqItem()
    {
        return $this->belongsTo(BoqItem::class);
    }
}
