<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionItem extends Model
{
    use SoftDeletes;

    protected $table = 'construction_items';

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'unit',
        'is_inventory_tracked',
        'standard_cost',
        'description',
    ];

    protected $casts = [
        'is_inventory_tracked' => 'boolean',
        'standard_cost' => 'decimal:2',
    ];

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'item_id');
    }
}
