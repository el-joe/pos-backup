<?php

namespace App\Traits;

use App\Models\Translation;

trait TranslationTrait
{
    function translations() {
        return $this->morphMany(Translation::class,'model');
    }

    function getValue($key) {
        $lang = request()->header('locale') ?? config('app.locale');
        return $this->translations
            ->where('key',$key)
            ->where('lang',$lang)
            ->first()?->value ?? "";

    }
}
