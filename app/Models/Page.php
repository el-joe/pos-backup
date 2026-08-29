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
        'page_type',
        'section',
        'sort_order',
        'youtube_videos',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'youtube_videos' => 'array',
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

    public function scopeDocumentation($query)
    {
        return $query->where('page_type', 'documentation');
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Convert a YouTube watch/share URL into a safe embed URL.
     * Only youtube.com and youtu.be hosts are accepted.
     */
    public function getYoutubeEmbedUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        $parts = parse_url($url);

        if (!$parts || empty($parts['host'])) {
            return '';
        }

        $host = strtolower($parts['host']);
        $host = preg_replace('/^www\./', '', $host);

        $videoId = '';

        if ($host === 'youtu.be') {
            $videoId = ltrim($parts['path'] ?? '', '/');
        } elseif ($host === 'youtube.com' || $host === 'm.youtube.com') {
            if (!empty($parts['path']) && str_starts_with($parts['path'], '/embed/')) {
                $videoId = substr($parts['path'], strlen('/embed/'));
            } else {
                parse_str($parts['query'] ?? '', $query);
                $videoId = $query['v'] ?? '';
            }
        } else {
            return '';
        }

        $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $videoId);

        if ($videoId === '') {
            return '';
        }

        return 'https://www.youtube.com/embed/' . $videoId;
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

    /**
     * Inject id="" anchors into h2/h3/h4 tags of the content and return
     * the rendered HTML alongside a flat table of contents.
     *
     * @return array{html: string, toc: array<int, array{id: string, text: string, level: int}>}
     */
    public function getContentWithToc(): array
    {
        $html = $this->content;

        if (trim($html) === '') {
            return ['html' => $html, 'toc' => []];
        }

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?><div id="__docs_root">' . $html . '</div>', LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        $toc = [];
        $used = [];
        $xpath = new \DOMXPath($doc);

        foreach ($xpath->query('//h2 | //h3 | //h4') as $heading) {
            $text = trim($heading->textContent);

            if ($text === '') {
                continue;
            }

            $slug = Str::slug($text, '-') ?: 'section';
            $id = $slug;
            $i = 2;

            while (isset($used[$id])) {
                $id = $slug . '-' . $i++;
            }

            $used[$id] = true;
            $heading->setAttribute('id', $id);

            $toc[] = [
                'id' => $id,
                'text' => $text,
                'level' => (int) substr($heading->nodeName, 1),
            ];
        }

        $root = $doc->getElementById('__docs_root');
        $renderedHtml = '';

        foreach (iterator_to_array($root->childNodes) as $child) {
            $renderedHtml .= $doc->saveHTML($child);
        }

        return ['html' => $renderedHtml, 'toc' => $toc];
    }
}
