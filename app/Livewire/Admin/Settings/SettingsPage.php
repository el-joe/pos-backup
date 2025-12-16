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
                if($s) $s->file()->create([
                    'path' => $file,
                    'key' => $key,
                ]);

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

        $this->alert('success', 'Settings saved successfully!');
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
            ->title('Settings');
    }
}
