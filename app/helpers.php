<?php

use Carbon\Carbon;

const TENANT_ADMINS_GUARD = 'tenant_admin';

function settings($key) {
    return null;
}

function admin() {
    return auth(TENANT_ADMINS_GUARD)->user();
}

function recursiveChildrenForOptions($model,$relationName,$key,$value,$number,$isLastChildCheck = true) {
    $arrayOfDashes = array_fill(0,$number,'-- ');
    $dashes = implode('',$arrayOfDashes);

    if(method_exists($model,'isLastChild')){
        $isLastChild = $model->isLastChild();
    }else{
        $isLastChild = $model->{$relationName}->count() == 0;
    }

    echo '<option value="'. $model->{$key} .'" '. ($isLastChildCheck ? ($isLastChild ? '' : 'disabled') : '') .'>'. $dashes . $model->{$value} .'</option>';

    $model->{$relationName}->map(fn($model1)=> recursiveChildrenForOptions($model1,$relationName,$key,$value,$number+1,$isLastChildCheck));
}

function carbon($date) {
    return Carbon::parse($date);
}

