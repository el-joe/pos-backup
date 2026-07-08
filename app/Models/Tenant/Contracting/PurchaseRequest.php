<?php

namespace App\Models\Tenant\Contracting;

use App\Models\Tenant\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use SoftDeletes;

    protected $table = 'contracting_purchase_requests';

    protected $fillable = [
        'code',
        'project_id',
        'requested_by',
        'required_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'required_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function requester()
    {
        return $this->belongsTo(Admin::class, 'requested_by')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
