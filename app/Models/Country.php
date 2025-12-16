<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $connection = 'central';
    protected $fillable = ['name', 'code'];
}
