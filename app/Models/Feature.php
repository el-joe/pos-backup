<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'type',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function plan_features()
    {
        return $this->hasMany(PlanFeature::class, 'feature_id');
    }
}
