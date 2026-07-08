<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;

class LaborTimesheet extends Model
{
    protected $table = 'labor_timesheets';

    protected $fillable = [
        'project_id',
        'worker_id',
        'project_task_id',
        'date',
        'hours_worked',
        'overtime_hours',
        'hourly_rate',
        'total_cost',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function projectTask()
    {
        return $this->belongsTo(ProjectTask::class);
    }
}
