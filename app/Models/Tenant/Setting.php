<?php

namespace App\Models\Tenant;

use App\Enums\TenantSettingEnum;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key', 'value', 'title', 'type', 'group','options'
    ];

    protected $casts = [
        'type' => TenantSettingEnum::class
    ];

    static function get($key, $default = null) {
        return tenantSetting($key, $default);
    }

    function file()
    {
        return $this->morphOne(File::class, 'model')->where('key', $this->key);
    }
}
