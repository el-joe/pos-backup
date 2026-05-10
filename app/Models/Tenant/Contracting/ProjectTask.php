<?php

namespace App\Models\Tenant\Contracting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
    use SoftDeletes;

    protected $table = 'project_tasks';

    protected $fillable = [
        'project_id',
        'parent_id',
        'boq_item_id',
        'name',
        'weight_percentage',
        'completion_percentage',
        'start_date',
        'end_date',
        'position',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'weight_percentage' => 'decimal:2',
        'completion_percentage' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function boqItem()
    {
        return $this->belongsTo(BoqItem::class);
    }
}
