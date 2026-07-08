<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoqItem extends Model
{
    use SoftDeletes;

    protected $table = 'boq_items';

    protected $fillable = [
        'boq_id',
        'parent_id',
        'item_code',
        'description',
        'unit',
        'estimated_quantity',
        'unit_rate',
        'total_price',
        'position',
    ];

    protected $casts = [
        'estimated_quantity' => 'decimal:3',
        'unit_rate' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function boq()
    {
        return $this->belongsTo(Boq::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function extractLines()
    {
        return $this->hasMany(ExtractLine::class);
    }
}
