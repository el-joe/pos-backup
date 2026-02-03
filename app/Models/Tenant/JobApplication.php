<?php

namespace App\Models\Tenant;

use App\Enums\JobApplicationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_number',
        'job_opening_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'country',
        'current_company',
        'current_designation',
        'current_salary',
        'expected_salary',
        'total_experience_years',
        'notice_period_days',
        'highest_qualification',
        'cover_letter',
        'resume_path',
        'additional_documents',
        'source',
        'referral_name',
        'status',
        'interview_date',
        'interview_time',
        'interview_notes',
        'rating',
        'remarks',
        'assigned_to',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'current_salary' => 'decimal:2',
        'expected_salary' => 'decimal:2',
        'interview_date' => 'date',
        'additional_documents' => 'array',
        'status' => JobApplicationStatusEnum::class,
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (!$application->application_number) {
                $application->application_number = 'APP-' . date('Ymd') . '-' . str_pad((JobApplication::whereDate('created_at', date('Y-m-d'))->max('id') + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
