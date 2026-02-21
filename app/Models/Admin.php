<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
    ];

    function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
