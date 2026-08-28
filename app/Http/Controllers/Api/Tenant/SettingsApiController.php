<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Models\Tenant\Setting;
use Illuminate\Http\Request;

class SettingsApiController extends ApiController
{
    protected function permission(): string
    {
        return 'api_settings.list,api_settings.update';
    }

    public function index(Request $request)
    {
        $settings = Setting::all()->mapWithKeys(fn ($setting) => [$setting->key => $setting->value]);

        return $this->success($settings);
    }

    public function regenerateToken(Request $request)
    {
        $token = admin()->generateApiToken();

        return $this->success([
            'token' => $token,
        ]);
    }
}
