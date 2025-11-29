<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $connection = 'central';

    protected $fillable = ['name', 'code', 'symbol', 'conversion_rate'];
}
