<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $connection = 'central';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
    ];
}
