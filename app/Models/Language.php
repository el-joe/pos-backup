<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $connection = 'central';
    protected $fillable = [
        'name','code','active'
    ];
}
