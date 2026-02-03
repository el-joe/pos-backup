<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'program_name',
        'ar_program_name',
        'description',
        'ar_description',
        'trainer_name',
        'training_type',
        'start_date',
        'end_date',
        'duration_hours',
        'location',
        'cost_per_participant',
        'max_participants',
        'objectives',
        'is_mandatory',
        'active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cost_per_participant' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'active' => 'boolean',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
