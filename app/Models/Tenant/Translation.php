<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'model_type','model_id','lang','key','value'
    ];

    public function model()
    {
        return $this->morphTo();
    }

    function setValueAttribute($value) {
        if(!is_string($value)){
            $value = json_encode($value);
        }
        $this->attributes['value'] = $value;
    }

    public function decodedValue()
    {
        return json_decode($this->value, true);
    }
}
