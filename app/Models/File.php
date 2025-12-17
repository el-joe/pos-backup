<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = ['key', 'path', 'storage_type', 'model_id', 'model_type'];

    protected $appends = ['full_path'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $model = $file->model_type;
            $getTableName = (new $model)->getTable();
            $fullPath = $getTableName;
            // $file->file_size = $file->path->getSize();

            if ($file->path instanceof UploadedFile) {
                // Uploaded from request
                $file->path = Storage::disk($file->storage_type ?? 'public')->putFile($fullPath, $file->path);
            } elseif (is_string($file->path) && filter_var($file->path, FILTER_VALIDATE_URL)) {
                $_file = str_replace('/storage/', '', parse_url($file->path, PHP_URL_PATH));

                $getContent = Storage::disk($file->storage_type ?? 'public')->get($_file);

                $tempPath = tempnam(sys_get_temp_dir(), 'urlfile');
                file_put_contents($tempPath, $getContent);

                $uploadedFile = new UploadedFile(
                    $tempPath,
                    basename($_file),
                    mime_content_type($tempPath),
                    null,
                    true
                );


                $file->path = Storage::disk($file->storage_type ?? 'public')
                    ->putFile($fullPath, $uploadedFile);
            }
        });

        static::deleting(function ($file) {
            Storage::disk($file->storage_type ?? 'public')->delete($file->path);
        });
    }

    public function model()
    {
        return $this->morphTo();
    }

    function scopeKey($q, $value)
    {
        return $q->where('key', $value);
    }

    function getFullPathAttribute()
    {
        return $this->path ? url(Storage::url($this->path)) : null;
    }
}
