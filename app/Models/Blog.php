<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

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

    static $sizes = [
        'thumb_image' => ['thumbImage',400, 250],
        'og_image' => ['ogImage',1200, 630],
        'image' => ['image',1920, 1080],
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = generateSlug(self::class, $blog->title_en);

            Artisan::call('app:generate-sitemap');
        });


        static::deleting(function ($blog) {
            Artisan::call('app:generate-sitemap');

            foreach (self::$sizes as $sizeKey => [$relation,$width, $height]) {
                $blog->{$relation}()->delete();
            }
        });
    }

    function generateImages($file){
        if($file instanceof UploadedFile){
            $value = $file;

            foreach (self::$sizes as $sizeKey => [$relation,$width, $height]) {
                $this->{$relation}()->delete();
                $this->{$relation}()->create([
                    'path' => self::optimizeImage($value,[$width, $height]),
                    'key' => $sizeKey,
                ]);
            }
        }
    }

    static function optimizeImage($value,$size = null){

        list($width, $height) = $size ?? [800, 600];

         // 1️⃣ تعريف الملف الأصلي
        if ($value instanceof UploadedFile) {
            $originalPath = $value->getRealPath();
            $originalName = $value->getClientOriginalName();
        } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
            $originalPath = tempnam(sys_get_temp_dir(), 'urlimg');
            file_put_contents($originalPath, file_get_contents($value));
            $originalName = basename(parse_url($value, PHP_URL_PATH));
        } elseif (is_string($value) && file_exists($value)) {
            $originalPath = $value;
            $originalName = basename($value);
        } else {
            throw new \Exception('Invalid image value');
        }

        // 2️⃣ مسار مؤقت للملف بعد التحسين
        $tempPath = tempnam(sys_get_temp_dir(), 'optimized') . '.webp';

        // 3️⃣ Resize / Convert / Optimize
        Image::load($originalPath)
            ->format('webp')
            ->quality(80)
            ->fit(Fit::Contain, $width, $height)
            ->optimize()
            ->save($tempPath);

        // 4️⃣ ارجع UploadedFile جاهز
        return new UploadedFile(
            $tempPath,
            pathinfo($originalName, PATHINFO_FILENAME) . '.webp',
            'image/webp',
            null,
            true // $test = true لأن الملف موجود مسبقًا
        );
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

    function thumbImage(){
        return $this->morphOne(File::class,'model')->key('thumb_image');
    }

    function getThumbImagePathAttribute()
    {
        if ($this->thumbImage) {
            return $this->thumbImage->full_path;
        }
        return "/hud/assets/img/landing/blog-1.jpg";
    }

    function ogImage(){
        return $this->morphOne(File::class,'model')->key('og_image');
    }

    function getOgImagePathAttribute()
    {
        if ($this->ogImage) {
            return $this->ogImage->full_path;
        }
        return "/hud/assets/img/landing/blog-1.jpg";
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true);
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
