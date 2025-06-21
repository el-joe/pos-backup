<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['name','rate'];
}
