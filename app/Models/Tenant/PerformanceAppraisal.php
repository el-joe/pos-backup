<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceAppraisal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appraisal_number',
        'employee_id',
        'appraisal_period',
        'start_date',
        'end_date',
        'appraisal_date',
        'appraiser_id',
        'reviewer_id',
        'kpi_scores',
        'overall_score',
        'overall_rating',
        'strengths',
        'areas_of_improvement',
        'goals_for_next_period',
        'employee_comments',
        'appraiser_comments',
        'reviewer_comments',
        'is_submitted',
        'is_acknowledged_by_employee',
        'acknowledged_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'appraisal_date' => 'date',
        'kpi_scores' => 'array',
        'overall_score' => 'decimal:2',
        'is_submitted' => 'boolean',
        'is_acknowledged_by_employee' => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function appraiser(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'appraiser_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appraisal) {
            if (!$appraisal->appraisal_number) {
                $appraisal->appraisal_number = 'APR-' . date('Y') . '-' . str_pad((PerformanceAppraisal::whereYear('created_at', date('Y'))->max('id') + 1), 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
