<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Faq extends Model
{
    protected $connection = 'central';

    // generate sitemap when create faq or delete faq
    protected static function booted()
    {
        static::created(function ($faq) {
            Artisan::call('app:generate-sitemap');
        });

        static::deleted(function ($faq) {
            Artisan::call('app:generate-sitemap');
        });
    }

    protected $fillable = [
        'question_en',
        'question_ar',
        'answer_en',
        'answer_ar',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getQuestionAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->question_ar ?: ($this->question_en ?: '');
        }

        return $this->question_en ?: ($this->question_ar ?: '');
    }

    public function getAnswerAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->answer_ar ?: ($this->answer_en ?: '');
        }

        return $this->answer_en ?: ($this->answer_ar ?: '');
    }
}
