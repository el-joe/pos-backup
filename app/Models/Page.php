<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'slug',
        'title_en',
        'title_ar',
        'short_description_en',
        'short_description_ar',
        'content_en',
        'content_ar',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $page) {
            $base = trim((string) ($page->slug ?: $page->title_en));
            $baseSlug = Str::slug($base, '-');

            if (!$baseSlug) {
                $baseSlug = Str::slug(uniqid(), '-');
            }

            if (!$page->exists) {
                $page->slug = generateSlug(self::class, $baseSlug);
                return;
            }

            $page->slug = $baseSlug;

            $conflict = self::query()
                ->where('slug', $page->slug)
                ->whereKeyNot($page->getKey())
                ->exists();

            if ($conflict) {
                $page->slug = generateSlug(self::class, $page->slug);
            }
        });

        static::saved(function (self $page) {
            Artisan::call('app:generate-sitemap');
        });

        static::deleted(function (self $page) {
            Artisan::call('app:generate-sitemap');
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->title_ar ?: ($this->title_en ?: '');
        }

        return $this->title_en ?: ($this->title_ar ?: '');
    }

    public function getShortDescriptionAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->short_description_ar ?: ($this->short_description_en ?: '');
        }

        return $this->short_description_en ?: ($this->short_description_ar ?: '');
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
