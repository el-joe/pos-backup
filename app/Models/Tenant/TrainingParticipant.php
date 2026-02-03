<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_program_id',
        'employee_id',
        'status',
        'attendance_percentage',
        'assessment_score',
        'feedback',
        'certificate_path',
        'completion_date',
    ];

    protected $casts = [
        'attendance_percentage' => 'integer',
        'assessment_score' => 'decimal:2',
        'completion_date' => 'date',
    ];

    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
