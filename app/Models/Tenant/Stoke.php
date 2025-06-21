<?php

namespace App\Models\Tenant;

use App\Models\Tenant\ProductVariable;
use App\Models\Tenant\Unit;
use Illuminate\Database\Eloquent\Model;

class Stoke extends Model
{
    protected $fillable = ['product_variable_id','unit_id','qty'];

    function productVariable() {
        return $this->belongsTo(ProductVariable::class,'product_variable_id');
    }

    function unit() {
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
