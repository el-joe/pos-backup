<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = ['title'];

    function image()
    {
        $this->morphOne(File::class, 'image')->key('image');
    }

    function getImagePathAttribute()
    {
        return $this->image ? $this->image->full_path : null;
    }
}
