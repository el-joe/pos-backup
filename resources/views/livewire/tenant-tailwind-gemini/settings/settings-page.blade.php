<div class="grid gap-6 xl:grid-cols-[18rem_minmax(0,1fr)]">
    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.settings.settings_groups')" icon="fa fa-sliders" :render-table="false">
        <div class="flex flex-col gap-2 p-4">
            @foreach($this->groups as $group)
                <button
                    type="button"
                    wire:click.prevent="setActiveGroup('{{ $group }}')"
                    class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-left text-sm font-medium transition-colors {{ $activeGroup === $group ? 'bg-brand-500 text-white' : 'border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}"
                >
                    <i class="fa fa-{{ $group === 'business' ? 'building' : ($group === 'product' ? 'box' : ($group === 'pos' ? 'cash-register' : 'cog')) }}"></i>
                    {{ ucfirst($group) }}
                </button>
            @endforeach
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.settings.' . $activeGroup)" icon="fa fa-cog" :render-table="false">
        <x-slot:actions>
            <button type="button" wire:click="save" class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600" wire:loading.attr="disabled">
                <span wire:loading.remove class="inline-flex items-center gap-2">
                    <i class="fa fa-save"></i> {{ __('general.pages.settings.save') }}
                </span>
                <span wire:loading class="inline-flex items-center gap-2">
                    <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                    {{ __('general.pages.settings.saving') }}
                </span>
            </button>
        </x-slot:actions>

        <div class="p-5">
            @if(isset($this->groupedSettings[$activeGroup]))
                <form wire:submit.prevent="save" class="grid gap-4 md:grid-cols-2">
                    @foreach($this->groupedSettings[$activeGroup] as $setting)
                        <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __($setting->title) }}
                            </label>

                            @switch($setting->type->value)
                                @case('string')
                                    <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="settings.{{ $setting->key }}" placeholder="{{ __($setting->title) }}">
                                    @break

                                @case('email')
                                    <input type="email" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="settings.{{ $setting->key }}" placeholder="{{ __($setting->title) }}">
                                    @break

                                @case('number')
                                    <input type="number" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="settings.{{ $setting->key }}" placeholder="{{ __($setting->title) }}">
                                    @break

                                @case('text')
                                    <textarea class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="settings.{{ $setting->key }}" rows="3" placeholder="{{ __($setting->title) }}"></textarea>
                                    @break

                                @case('boolean')
                                    <label class="inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 dark:border-slate-700 dark:bg-slate-800/70">
                                        <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" wire:model="settings.{{ $setting->key }}" id="{{ $setting->key }}" value="1" {{ ($settings[$setting->key] ?? false) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Enable</span>
                                    </label>
                                    @break

                                @case('select')
                                    <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="settings.{{ $setting->key }}">
                                        <option value="">-- Select --</option>
                                        @if($setting->key === 'currency_id')
                                            @foreach($this->currencies as $currency)
                                                <option value="{{ $currency->id }}" {{ ($settings[$setting->key] ?? '') == $currency->id ? 'selected' : '' }}>{{ $currency->name }} ({{ $currency->code }})</option>
                                            @endforeach
                                        @elseif($setting->key === 'country_id')
                                            @foreach($this->countries as $country)
                                                <option value="{{ $country->id }}" {{ ($settings[$setting->key] ?? '') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                            @endforeach
                                        @elseif($setting->key === 'default_language')
                                            @foreach($this->languages as $language)
                                                <option value="{{ $language->code }}" {{ ($settings[$setting->key] ?? '') == $language->code ? 'selected' : '' }}>{{ $language->name }}</option>
                                            @endforeach
                                        @elseif($setting->key === 'default_tax')
                                            @foreach($this->taxes as $tax)
                                                <option value="{{ $tax->id }}" {{ ($settings[$setting->key] ?? '') == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>
                                            @endforeach
                                        @elseif($setting->options)
                                            @php($options = json_decode($setting->options, true))
                                            @foreach($options as $optionKey => $optionValue)
                                                <option value="{{ $optionKey }}" {{ ($settings[$setting->key] ?? '') == $optionKey ? 'selected' : '' }}>{{ $optionValue }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @break

                                @case('file')
                                    <div class="space-y-3">
                                        @if($settings[$setting->key] ?? null)
                                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800/70">
                                                <img src="{{ $setting->key == 'logo' ? ($settings[$setting->key] ?? asset('mohaaseb_en_dark.png')) : $settings[$setting->key] ?? '' }}" alt="Current logo" class="max-h-24 rounded-xl object-contain">
                                            </div>
                                        @endif
                                        <input type="file" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model="uploadedFiles.{{ $setting->key }}" accept="image/*">
                                    </div>
                                    @break

                                @case('date')
                                    <input type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model="settings.{{ $setting->key }}">
                                    @break

                                @case('url')
                                    <input type="url" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="settings.{{ $setting->key }}" placeholder="https://example.com">
                                    @break

                                @case('password')
                                    <input type="password" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="settings.{{ $setting->key }}" placeholder="{{ __($setting->title) }}">
                                    @break

                                @default
                                    <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="settings.{{ $setting->key }}" placeholder="{{ __($setting->title) }}">
                            @endswitch

                            @error('settings.' . $setting->key)
                                <div class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </form>
            @else
                <div class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-700 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-200">
                    <i class="fa fa-info-circle"></i>
                    No settings found for this group.
                </div>
            @endif
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
