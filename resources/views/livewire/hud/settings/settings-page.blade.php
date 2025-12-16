<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-xl-2 col-lg-3 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Settings Groups') }}</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($this->groups as $group)
                        <a href="#"
                           wire:click.prevent="setActiveGroup('{{ $group }}')"
                           class="list-group-item list-group-item-action {{ $activeGroup === $group ? 'active' : '' }}">
                            <i class="fa fa-{{ $group === 'business' ? 'building' : ($group === 'product' ? 'box' : ($group === 'pos' ? 'cash-register' : 'cog')) }} me-2"></i>
                            {{ ucfirst($group) }}
                        </a>
                    @endforeach
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-xl-10 col-lg-9 col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ ucfirst($activeGroup) }} Settings</h4>
                    <button type="button" wire:click="save" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="fa fa-save me-1"></i> Save Settings
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Saving...
                        </span>
                    </button>
                </div>

                <div class="card-body">
                    @if(isset($this->groupedSettings[$activeGroup]))
                        <form wire:submit.prevent="save">
                            <div class="row g-4">
                                @foreach($this->groupedSettings[$activeGroup] as $setting)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                {{ __($setting->title) }}
                                            </label>

                                            @switch($setting->type->value)
                                                @case('string')
                                                    <input type="text"
                                                           class="form-control"
                                                           wire:model="settings.{{ $setting->key }}"
                                                           placeholder="{{ __($setting->title) }}">
                                                    @break

                                                @case('email')
                                                    <input type="email"
                                                           class="form-control"
                                                           wire:model="settings.{{ $setting->key }}"
                                                           placeholder="{{ __($setting->title) }}">
                                                    @break

                                                @case('number')
                                                    <input type="number"
                                                           class="form-control"
                                                           wire:model="settings.{{ $setting->key }}"
                                                           placeholder="{{ __($setting->title) }}">
                                                    @break

                                                @case('text')
                                                    <textarea class="form-control"
                                                              wire:model="settings.{{ $setting->key }}"
                                                              rows="3"
                                                              placeholder="{{ __($setting->title) }}"></textarea>
                                                    @break

                                                @case('boolean')
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox"
                                                               class="form-check-input"
                                                               wire:model="settings.{{ $setting->key }}"
                                                               id="{{ $setting->key }}"
                                                               value="1"
                                                               {{ ($settings[$setting->key] ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="{{ $setting->key }}">
                                                            Enable
                                                        </label>
                                                    </div>
                                                    @break

                                                @case('select')
                                                    <select class="form-select" wire:model="settings.{{ $setting->key }}">
                                                        <option value="">-- Select --</option>
                                                        @if($setting->key === 'currency_id')
                                                            @foreach($this->currencies as $currency)
                                                                <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                                                            @endforeach
                                                        @elseif($setting->key === 'country_id')
                                                            @foreach($this->countries as $country)
                                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                            @endforeach
                                                        @elseif($setting->key === 'default_language')
                                                            @foreach($this->languages as $language)
                                                                <option value="{{ $language->code }}">{{ $language->name }}</option>
                                                            @endforeach
                                                        @elseif($setting->key === 'default_tax')
                                                            @foreach($this->taxes as $tax)
                                                                <option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->rate }}%)</option>
                                                            @endforeach
                                                        @elseif($setting->options)
                                                            @php
                                                                $options = json_decode($setting->options, true);
                                                            @endphp
                                                            @foreach($options as $optionKey => $optionValue)
                                                                <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @break

                                                @case('file')
                                                    <div>
                                                        @if($settings[$setting->key] ?? null)
                                                            <div class="mb-2">
                                                                <img src="{{ $settings[$setting->key] }}"
                                                                     alt="Current logo"
                                                                     class="img-thumbnail"
                                                                     style="max-height: 100px;">
                                                            </div>
                                                        @endif
                                                        <input type="file"
                                                               class="form-control"
                                                               wire:model="uploadedFiles.{{ $setting->key }}"
                                                               accept="image/*">
                                                    </div>
                                                    @break

                                                @case('date')
                                                    <input type="date"
                                                           class="form-control"
                                                           wire:model="settings.{{ $setting->key }}">
                                                    @break

                                                @case('url')
                                                    <input type="url"
                                                           class="form-control"
                                                           wire:model="settings.{{ $setting->key }}"
                                                           placeholder="https://example.com">
                                                    @break

                                                @case('password')
                                                    <input type="password"
                                                           class="form-control"
                                                           wire:model="settings.{{ $setting->key }}"
                                                           placeholder="{{ __($setting->title) }}">
                                                    @break

                                                @default
                                                    <input type="text"
                                                           class="form-control"
                                                           wire:model="settings.{{ $setting->key }}"
                                                           placeholder="{{ __($setting->title) }}">
                                            @endswitch

                                            @error('settings.' . $setting->key)
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            No settings found for this group.
                        </div>
                    @endif
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
    </div>
</div>
