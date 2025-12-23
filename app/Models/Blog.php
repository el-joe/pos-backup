<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'slug',
        'title_en',
        'title_ar',
        'excerpt_en',
        'excerpt_ar',
        'content_en',
        'content_ar',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = generateSlug(self::class, $blog->title_en);

            Artisan::call('app:generate-sitemap');
        });
    }

    function image(){
        return $this->morphOne(File::class,'model')->key('image');
    }

    function getImagePathAttribute()
    {
        if ($this->image) {
            return $this->image->full_path;
        }
        return "/hud/assets/img/landing/blog-1.jpg";
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->title_ar ?: ($this->title_en ?: '');
        }

        return $this->title_en ?: ($this->title_ar ?: '');
    }

    public function getExcerptAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->excerpt_ar ?: ($this->excerpt_en ?: '');
        }

        return $this->excerpt_en ?: ($this->excerpt_ar ?: '');
    }

    public function getContentAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->content_ar ?: ($this->content_en ?: '');
        }

        return $this->content_en ?: ($this->content_ar ?: '');
    }
}
