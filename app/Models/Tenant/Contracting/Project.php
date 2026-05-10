<?php

namespace App\Models\Tenant\Contracting;

use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'code',
        'name',
        'client_id',
        'cost_center_id',
        'tender_id',
        'status',
        'start_date',
        'end_date',
        'total_budget',
        'total_contract_value',
        'location',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_budget' => 'decimal:2',
        'total_contract_value' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function boqs()
    {
        return $this->hasMany(Boq::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function laborTimesheets()
    {
        return $this->hasMany(LaborTimesheet::class);
    }

    public function equipmentLogs()
    {
        return $this->hasMany(EquipmentLog::class);
    }
}
