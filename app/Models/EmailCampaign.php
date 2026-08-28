<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'subject_en',
        'subject_ar',
        'body_en',
        'body_ar',
        'recipient_type',
        'manual_emails',
        'status',
        'sent_count',
        'scheduled_at',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'sent_count' => 'integer',
    ];
}
