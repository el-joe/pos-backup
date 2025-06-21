<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = ['key','path','storage_type','model_id','model_type'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $model = $file->model_type;

            $getTableName = (new $model)->getTable();
            $fullPath = $getTableName;

            $file->path =  Storage::disk($file->storage_type ?? 'public')->putFile($fullPath, $file->path);
        });

        static::deleting(function ($file) {
            Storage::disk($file->storage_type ?? 'public')->delete($file->path);
        });
    }

    public function model()
    {
        return $this->morphTo();
    }

    function getFullPathAttribute() {
        return url(Storage::url($this->path));
    }
}
