<?php

namespace App\Models\Tenant\Contracting;

use App\Models\Tenant\Admin;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $table = 'inventory_transactions';

    protected $fillable = [
        'warehouse_id',
        'project_id',
        'item_id',
        'type',
        'quantity',
        'unit_cost',
        'total_cost',
        'transaction_date',
        'source_type',
        'source_id',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function item()
    {
        return $this->belongsTo(ConstructionItem::class, 'item_id');
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by')->withTrashed();
    }
}
