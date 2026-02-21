<?php

namespace App\Traits;

use App\Models\Translation;

trait Translator
{
    public function __get($name)
    {
        if(in_array($name,$this->translated??[])){
            return $this->t($name);
        }

        return parent::__get($name);
    }

    function translations() {
        return $this->morphMany(Translation::class, 'model');
    }

    function t($key) {
        $translations = $this->translations ?? $this->getRelations()['translations'];
        return $translations
            ->where('locale', 'ar')
            // ->where('locale', app()->getLocale())
            ->where('key',$key)
            ->first()->value ?? null;
    }
}
