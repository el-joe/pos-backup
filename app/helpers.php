<?php

use Carbon\Carbon;

const TENANT_ADMINS_GUARD = 'tenant_admin';

if(!function_exists('settings')) {
    function settings($key) {
        return null;
    }
}

if(!function_exists('admin')) {
    function admin() {
        return auth(TENANT_ADMINS_GUARD)->user();
    }
}

function branch() {
    return auth(TENANT_ADMINS_GUARD)->user()->branch;
}

function recursiveChildrenForOptions($model,$relationName,$key,$value,$number,$isLastChildCheck = true,$selectedId = null) {
    $arrayOfDashes = array_fill(0,$number,'-- ');
    $dashes = implode('',$arrayOfDashes);

    if(method_exists($model,'isLastChild')){
        $isLastChild = $model->isLastChild();
    }else{
        $isLastChild = $model->{$relationName}->count() == 0;
    }
    // add
    echo '<option value="'. $model->{$key} .'" '. ($isLastChildCheck ? ($isLastChild ? '' : 'disabled') : '') . ($selectedId == $model->{$key} ? 'selected' : '') .'>'. $dashes . $model->{$value} .'</option>';

    $model->{$relationName}->map(fn($model1)=> recursiveChildrenForOptions($model1,$relationName,$key,$value,$number+1,$isLastChildCheck));
}

function carbon($date) {
    return Carbon::parse($date);
}

function formattedDate($date): string {
    return carbon($date)->translatedFormat('l , d-M-Y');
}

