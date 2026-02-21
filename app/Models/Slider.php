<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = ['title', 'number', 'active'];

    function image() {
        return $this->morphOne(File::class, 'model')->key('image');
    }

    function getImagePathAttribute() {
        return $this->image->full_path ?? asset('hud/assets/img/no_image.jpg');
    }
}
