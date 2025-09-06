<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface BaseInterface {
    public function setInstance(Model|Builder $model);
}
