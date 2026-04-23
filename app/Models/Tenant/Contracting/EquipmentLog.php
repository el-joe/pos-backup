<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;

class EquipmentLog extends Model
{
    protected $table = 'equipment_logs';

    protected $fillable = [
        'equipment_id',
        'project_id',
        'project_task_id',
        'date',
        'hours_used',
        'cost_rate',
        'total_cost',
        'operator',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'hours_used' => 'decimal:2',
        'cost_rate' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function projectTask()
    {
        return $this->belongsTo(ProjectTask::class);
    }
}
