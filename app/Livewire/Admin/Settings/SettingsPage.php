<?php

namespace App\Livewire\Admin\Settings;

use App\Enums\TenantSettingEnum;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Tenant\Setting;
use App\Models\Tenant\Tax;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsPage extends Component
{
    use LivewireOperations, WithFileUploads;

    #[Url]
    public $activeGroup = 'business';
    public $settings = [];
    public $uploadedFiles = [];

    function boot(){
        if(Setting::count() == 0){
            $this->defaultSettings();
        }
    }

    function defaultSettings() {
        $data = [
            [
                'title' => 'settings.business_name',
                'key' => 'business_name',
                'value' => tenant('name') ?? '',
                'type' => TenantSettingEnum::STRING->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.business_email',
                'key' => 'business_email',
                'value' => tenant('email') ?? '',
                'type' => TenantSettingEnum::EMAIL->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.business_phone',
                'key' => 'business_phone',
                'value' => tenant('phone') ?? '',
                'type' => TenantSettingEnum::STRING->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.business_address',
                'key' => 'business_address',
                'value' => tenant('address') ?? '',
                'type' => TenantSettingEnum::STRING->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.default_currency',
                'key' => 'currency_id',
                'value' => tenant('currency_id') ?? 1,
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => null
            ],
            [
                'title' => 'settings.default_country',
                'key' => 'country_id',
                'value' => tenant('country_id') ?? 1,
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => null
            ],
            [
                'title' => 'settings.tax_number',
                'key' => 'tax_number',
                'value' => tenant('tax_number') ?? '',
                'type' => TenantSettingEnum::STRING->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.logo',
                'key' => 'logo',
                'value' => '',
                'type' => TenantSettingEnum::FILE->value,
                'group' => 'business',
                'options' => null
            ],
            [
                'title' => 'settings.date_format',
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => json_encode([
                    'Y-m-d' => 'Y-m-d',
                    'd-m-Y' => 'd-m-Y',
                    'm-d-Y' => 'm-d-Y',
                    'd/m/Y' => 'd/m/Y',
                    'm/d/Y' => 'm/d/Y',
                    'Y/m/d' => 'Y/m/d',
                ])
            ],
            [
                'title' => 'settings.time_format',
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => json_encode([
                    'H:i' => '24-hour (14:00)',
                    'h:i A' => '12-hour (02:00 PM)',
                ])
            ],
            [
                'title' => 'settings.currency_precision',
                'key' => 'currency_precision',
                'value' => '2',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => json_encode([
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ])
            ],
            [
                'title' => 'settings.qty_precision',
                'key' => 'qty_precision',
                'value' => '2',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => json_encode([
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ])
            ],
            [
                'title' => 'settings.enable_brands',
                'key' => 'enable_brands',
                'value' => '1',
                'type' => TenantSettingEnum::BOOLEAN->value,
                'group' => 'product',
                'options' => null
            ],
            [
                'title' => 'settings.enable_categories',
                'key' => 'enable_categories',
                'value' => '1',
                'type' => TenantSettingEnum::BOOLEAN->value,
                'group' => 'product',
                'options' => null
            ],
            [
                'title' => 'settings.default_tax',
                'key' => 'default_tax',
                'value' => null,
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'pos',
                'options' => null
            ],
            // [
            //     'title' => 'settings.theme_color',
            //     'key' => 'theme_color',
            //     'value' => '',
            //     'type' => TenantSettingEnum::SELECT->value,
            //     'group' => 'system',
            //     'options' => json_encode([
            //         'theme-pink' => 'Pink',
            //         'theme-red' => 'Red',
            //         'theme-warning' => 'Warning',
            //         'theme-yellow' => 'Yellow',
            //         'theme-lime' => 'Lime',
            //         'theme-green' => 'Green',
            //         '' => 'Teal',
            //         'theme-info' => 'Info',
            //         'theme-primary' => 'Primary',
            //         'theme-purple' => 'Purple',
            //         'theme-indigo' => 'Indigo',
            //         'theme-gray-200' => 'Gray 200',
            //     ])
            // ],
            [
                'title' => 'settings.default_language',
                'key' => 'default_language',
                'value' => 'en',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'system',
                'options' => null
            ],
            // [
            //     'title' => 'settings.theme_mode',
            //     'key' => 'theme_mode',
            //     'value' => 'light',
            //     'type' => TenantSettingEnum::SELECT->value,
            //     'group' => 'system',
            //     'options' => json_encode([
            //         'light' => 'Light',
            //         'dark' => 'Dark',
            //     ])
            // ]
        ];

        foreach ($data as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                $item
            );
        }
    }

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $allSettings = Setting::all();

        foreach ($allSettings as $setting) {
            $this->settings[$setting->key] = $setting->value;
        }
    }

    public function setActiveGroup($group)
    {
        $this->activeGroup = $group;
    }

    public function save()
    {
        foreach ($this->settings as $key => $value) {
            // Handle file uploads
            if (isset($this->uploadedFiles[$key])) {
                $file = $this->uploadedFiles[$key];
                $s = Setting::where('key', $key)->first();
                if($s) {
                    $s->file()->delete();
                    $s->file()->create([
                        'path' => $file,
                        'key' => $key,
                    ]);
                }

                $s->update([
                    'value' => $s->file->full_path ?? ''
                ]);
            }else{
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        cache()->driver('file')->forget(cacheKey('setting'));
        cache()->driver('file')->forget(cacheKey('currency'));

        $this->alert('success', __('general.messages.settings_saved_successfully'));
        $this->js('setTimeout(() => { location.reload(); }, 1000);');
    }

    public function getGroupedSettingsProperty()
    {
        return Setting::all()->groupBy('group') ?? [];
    }

    public function getGroupsProperty()
    {
        return Setting::select('group')->distinct()->pluck('group')->toArray() ?? [];
    }

    public function getCountriesProperty()
    {
        return Country::all();
    }

    public function getCurrenciesProperty()
    {
        return Currency::all();
    }

    public function getLanguagesProperty()
    {
        return Language::all();
    }

    public function getTaxesProperty()
    {
        return Tax::where('active', true)->get();
    }

    public function render()
    {
        return layoutView('settings.settings-page')
            ->title(__('general.titles.settings'));
    }
}
