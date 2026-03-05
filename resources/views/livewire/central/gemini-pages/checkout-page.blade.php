<main class="flex-grow container mx-auto px-6 py-28" itemscope itemtype="https://schema.org/WebPage">
    <div class="flex flex-col lg:flex-row gap-12 max-w-6xl mx-auto">

        <div class="lg:w-2/3">
            <h1 class="text-2xl font-bold text-brand-dark dark:text-white mb-2">{{ __('gemini-landing.checkout_page.title') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 mb-8">
                @if($hasAnyFreeTrial ?? false)
                    {{ __('gemini-landing.checkout_page.subtitle_trial') }}
                @else
                    {{ __('gemini-landing.checkout_page.subtitle') }}
                @endif
            </p>

            <form wire:submit.prevent="completeSubscription" class="space-y-8">
                <div class="bg-white grid gap-4 dark:bg-slate-800 p-5 lg:p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                    <div class="flex items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-slate-700 text-brand-500 dark:text-brand-400 flex shrink-0 items-center justify-center font-bold border border-brand-100 dark:border-slate-600">1</div>
                        <h2 class="text-lg font-bold text-brand-dark dark:text-white">{{ __('gemini-landing.checkout_page.company_details') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">

                        <div class="lg:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.company_name') }}</label>
                            <input type="text" wire:model.live="data.company_name" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_company_name') }}">
                            @error('data.company_name') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.company_email') }}</label>
                            <input type="email" wire:model.live="data.company_email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_company_email') }}">
                            @error('data.company_email') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.company_phone') }}</label>
                            <div wire:ignore>
                                <input
                                    id="company_phone_iti"
                                    type="tel"
                                    inputmode="tel"
                                    autocomplete="tel"
                                    class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:text-white transition"
                                    placeholder="{{ __('gemini-landing.checkout_page.ph_company_phone') }}"
                                    value="{{ $data['company_phone'] ?? '' }}"
                                >
                            </div>
                            <input id="company_phone_value" type="hidden" wire:model.live="data.company_phone" value="{{ $data['company_phone'] ?? '' }}">
                            @error('data.company_phone') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.company_size') }}</label>
                            <select wire:model.live="data.company_size" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                <option value="">{{ __('gemini-landing.checkout_page.select') }}</option>
                                <option value="1-10">{{ __('gemini-landing.checkout_page.company_size_1_10') }}</option>
                                <option value="11-50">{{ __('gemini-landing.checkout_page.company_size_11_50') }}</option>
                                <option value="50+">{{ __('gemini-landing.checkout_page.company_size_50_plus') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.tax_number_optional') }}</label>
                            <input type="text" wire:model.live="data.tax_number" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_tax_number') }}">
                            @error('data.tax_number') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.country') }}</label>
                            <select wire:model.live="data.country_id" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                <option value="">{{ __('gemini-landing.checkout_page.select_country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" data-code="{{ strtolower((string) ($country->code ?? '')) }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('data.country_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.currency') }}</label>
                            <select wire:model.live="data.currency_id" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                <option value="">{{ __('gemini-landing.checkout_page.select_currency') }}</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                                @endforeach
                            </select>
                            @error('data.currency_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.address_optional') }}</label>
                            <input type="text" wire:model.live="data.address" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_address') }}">
                            @error('data.address') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-2">{{ __('gemini-landing.checkout_page.domain') }}</label>
                        <div class="flex flex-wrap gap-4">
                            <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300 cursor-pointer">
                                <input type="radio" class="w-4 h-4" value="subdomain" wire:model.live="data.domain_mode">
                                {{ __('gemini-landing.checkout_page.domain_use_subdomain') }}
                            </label>
                            <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300 cursor-pointer">
                                <input type="radio" class="w-4 h-4" value="domain" wire:model.live="data.domain_mode">
                                {{ __('gemini-landing.checkout_page.domain_use_custom') }}
                            </label>
                        </div>
                        @error('data.domain_mode') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror

                        @if(($data['domain_mode'] ?? 'subdomain') === 'subdomain')
                            <div class="mt-4">
                                <input type="text" wire:model.live.debounce.500ms="data.subdomain" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_subdomain') }}">
                                <div class="text-xs text-slate-400 mt-2">{{ __('gemini-landing.checkout_page.domain_preview') }}: <span class="font-semibold">{{ $data['final_domain'] ?? '--' }}</span></div>
                            </div>
                        @else
                            <div class="mt-4">
                                <input type="text" wire:model.live="data.domain" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_domain') }}">
                            </div>
                        @endif
                        @error('data.final_domain') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                    <div class="flex items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-slate-700 text-brand-500 dark:text-brand-400 flex items-center justify-center font-bold border border-brand-100 dark:border-slate-600">2</div>
                        <h2 class="text-lg font-bold text-brand-dark dark:text-white">{{ __('gemini-landing.checkout_page.admin_login_details') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.first_name') }}</label>
                            <input type="text" wire:model.live="data.admin_first_name" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_first_name') }}">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.last_name') }}</label>
                            <input type="text" wire:model.live="data.admin_last_name" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_last_name') }}">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.work_email') }}</label>
                            <input type="email" wire:model.live="data.admin_email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_work_email') }}">
                            @error('data.admin_email') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.password') }}</label>
                            <input type="password" wire:model.live="data.admin_password" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="••••••••">
                            @error('data.admin_password') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.confirm_password') }}</label>
                            <input type="password" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                @if($payableNow > 0)
                    <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                        <div class="flex items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                            <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-slate-700 text-brand-500 dark:text-brand-400 flex items-center justify-center font-bold border border-brand-100 dark:border-slate-600">3</div>
                            <h2 class="text-lg font-bold text-brand-dark dark:text-white">{{ __('gemini-landing.checkout_page.payment_method') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-2">
                            @forelse($paymentMethods as $pm)
                                <label class="cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex flex-col items-center gap-3 hover:border-brand-500 transition {{ ((int)($data['payment_method_id'] ?? 0)) === (int)$pm->id ? 'border-brand-500 bg-brand-50 dark:bg-slate-700' : 'bg-slate-50 dark:bg-slate-900' }}">
                                    <input type="radio" wire:model.live="data.payment_method_id" value="{{ $pm->id }}" class="hidden">

                                    @php
                                        $iconValue = (string)($pm->icon_path ?? '');
                                        $looksLikeImage = $iconValue !== '' && (
                                            str_contains($iconValue, '/') ||
                                            str_contains($iconValue, '.png') ||
                                            str_contains($iconValue, '.jpg') ||
                                            str_contains($iconValue, '.jpeg') ||
                                            str_contains($iconValue, '.gif') ||
                                            str_contains($iconValue, '.svg')
                                        );
                                    @endphp

                                    @if($iconValue !== '' && !$looksLikeImage)
                                        <i class="{{ $iconValue }} text-2xl text-slate-400 dark:text-slate-300"></i>
                                    @elseif($iconValue !== '' && $looksLikeImage)
                                        <img src="{{ asset('storage/' . $iconValue) }}" alt="{{ $pm->name }}" class="h-8 w-8 object-contain">
                                    @else
                                        <i class="fa-solid fa-money-bill-wave text-2xl text-slate-400 dark:text-slate-300"></i>
                                    @endif

                                    <span class="text-sm font-bold text-slate-700 dark:text-white text-center">{{ $pm->name }}</span>
                                </label>
                            @empty
                                <div class="col-span-3 text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('gemini-landing.checkout_page.no_payment_methods') }}
                                </div>
                            @endforelse
                        </div>
                        @error('data.payment_method_id') <div class="text-xs text-red-500 mt-1 mb-4">{{ $message }}</div> @enderror

                        @if(($selectedPaymentMethod->manual ?? false))
                            <div class="bg-blue-50 dark:bg-slate-900 border border-blue-100 dark:border-slate-700 rounded-xl p-6 mt-6">
                                <h4 class="text-sm font-bold text-blue-900 dark:text-blue-400 mb-2">{{ __('gemini-landing.checkout_page.manual_payment_title') }}</h4>
                                <p class="text-xs text-blue-800 dark:text-slate-400 mb-4">{{ __('gemini-landing.checkout_page.manual_payment_subtitle') }}</p>

                                @php
                                    $details = $selectedPaymentMethod->details ?? [];
                                    $locale = app()->getLocale();
                                @endphp

                                @if(is_array($details) && count($details) > 0)
                                    <div class="bg-white dark:bg-slate-800 p-4 rounded-lg mb-4 text-sm text-slate-700 dark:text-slate-300 space-y-2 border border-blue-50 dark:border-slate-700">
                                        @if(array_is_list($details))
                                            @foreach($details as $row)
                                                @php
                                                    $label = $row['label'][$locale] ?? ($row['label']['en'] ?? ($row['key'] ?? ''));
                                                    $value = $row['value'][$locale] ?? ($row['value']['en'] ?? '');
                                                @endphp
                                                <div class="flex justify-between gap-4 border-b border-slate-100 dark:border-slate-700 pb-1 last:border-b-0 last:pb-0">
                                                    <span class="font-bold">{{ $label }}:</span>
                                                    <span class="text-right">{{ $value }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach($details as $key => $value)
                                                @php
                                                    $label = is_array($value) ? ($value[$locale] ?? ($value['en'] ?? $key)) : $key;
                                                    $val = is_array($value) ? ($value[$locale] ?? ($value['en'] ?? '')) : $value;
                                                @endphp
                                                <div class="flex justify-between gap-4 border-b border-slate-100 dark:border-slate-700 pb-1 last:border-b-0 last:pb-0">
                                                    <span class="font-bold">{{ $label }}:</span>
                                                    <span class="text-right">{{ $val }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-2">{{ __('gemini-landing.checkout_page.upload_receipt') }} <span class="text-red-500">*</span></label>
                                    <input type="file" wire:model="receiptFile" class="block w-full text-sm text-slate-500 dark:text-slate-400
                                        file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-brand-50 file:text-brand-700
                                        hover:file:bg-brand-100 dark:file:bg-slate-700 dark:file:text-brand-400
                                        border border-slate-200 dark:border-slate-700 rounded-lg p-2 bg-white dark:bg-slate-800 cursor-pointer"
                                        accept=".pdf, .jpg, .jpeg, .png">
                                    <div wire:loading wire:target="receiptFile" class="text-xs text-brand-500 mt-2 font-medium"><i class="fa-solid fa-spinner fa-spin mr-1"></i> {{ __('gemini-landing.checkout_page.uploading_receipt') }}</div>
                                    @error('receiptFile') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="flex items-start gap-3">
                    <input type="checkbox" id="terms" wire:model.live="data.terms_conditions_agree" class="mt-1 w-5 h-5 text-brand-500 border-slate-300 dark:border-slate-600 rounded focus:ring-brand-500 cursor-pointer bg-white dark:bg-slate-900">
                    <label for="terms" class="text-sm text-slate-500 dark:text-slate-400">
                        {!! __('gemini-landing.checkout_page.terms_html', [
                            'terms' => '<a href="terms.html" class="text-brand-500 hover:underline">' . __('gemini-landing.checkout_page.terms_of_service') . '</a>',
                            'privacy' => '<a href="privacy.html" class="text-brand-500 hover:underline">' . __('gemini-landing.checkout_page.privacy_policy') . '</a>',
                        ]) !!}
                    </label>
                </div>
                @error('data.terms_conditions_agree') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror

                <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold text-lg rounded-xl hover:bg-brand-600 transition shadow-lg shadow-brand-500/30 transform hover:-translate-y-0.5 disabled:opacity-60 disabled:cursor-not-allowed" wire:loading.attr="disabled" @if(empty($data['terms_conditions_agree'])) disabled @endif>
                    <span wire:loading.remove wire:target="completeSubscription">{{ __('gemini-landing.checkout_page.submit') }}</span>
                    <span wire:loading wire:target="completeSubscription"><i class="fa-solid fa-spinner fa-spin mr-2"></i> {{ __('gemini-landing.checkout_page.processing') }}</span>
                </button>

                <p class="text-center text-sm text-slate-400 dark:text-slate-500 mt-4"><i class="fa-solid fa-lock mr-1"></i> {{ __('gemini-landing.checkout_page.security_note') }}</p>

            </form>
        </div>

        <div class="lg:w-1/3">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 sticky top-8 transition-colors duration-300">
                <h3 class="text-lg font-bold text-brand-dark dark:text-white mb-4 border-b border-slate-100 dark:border-slate-700 pb-4">{{ __('gemini-landing.checkout_page.order_summary') }}</h3>

                <div class="bg-brand-50 dark:bg-slate-700 border border-brand-100 dark:border-slate-600 rounded-xl p-4 mb-6">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wide">{{ __('gemini-landing.checkout_page.selected_systems') }}</span>
                        <a href="{{ route('pricing') }}" class="text-xs font-bold text-slate-400 hover:text-brand-500 underline">{{ __('gemini-landing.checkout_page.change') }}</a>
                    </div>
                    <div class="space-y-2">
                        @foreach(($selectedSystemsSummary ?? []) as $systemSummary)
                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <div class="font-semibold text-brand-900 dark:text-white">{{ $systemSummary['module_title'] ?? __('gemini-landing.checkout_page.system_fallback') }}</div>
                                    <div class="text-slate-500 dark:text-slate-300">{{ $systemSummary['plan_name'] ?? __('gemini-landing.checkout_page.plan_fallback') }}</div>
                                </div>
                                <div class="text-right">
                                    @if(((int) ($systemSummary['free_trial_months'] ?? 0)) > 0)
                                        <div class="font-bold text-green-600 dark:text-green-400">{{ __('gemini-landing.checkout_page.free_now') }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-300">{{ __('gemini-landing.checkout_page.trial_months', ['months' => (int) ($systemSummary['free_trial_months'] ?? 0)]) }}</div>
                                    @else
                                        <div class="font-bold text-brand-700 dark:text-brand-300">${{ number_format((float) ($systemSummary['price'] ?? 0), 2) }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-300">/{{ ($period ?? 'month') === 'year' ? __('gemini-landing.common.period_year_short') : __('gemini-landing.common.period_month_short') }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-xs font-bold text-slate-400 uppercase mb-3">{{ __('gemini-landing.checkout_page.includes') }}</h4>
                    <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        @forelse(($selectedFeatureNames ?? []) as $featureName)
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500 text-xs"></i> {{ $featureName }}</li>
                        @empty
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500 text-xs"></i> {{ __('gemini-landing.checkout_page.core_modules_enabled') }}</li>
                        @endforelse
                    </ul>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-700 pt-4 flex justify-between items-center mb-2">
                    <span class="font-bold text-slate-700 dark:text-white">{{ __('gemini-landing.checkout_page.total_due_today') }}</span>
                    <span class="text-2xl font-extrabold text-brand-dark dark:text-white">
                        @if(((float) ($selectedDueNow ?? 0)) <= 0)
                            {{ __('gemini-landing.checkout_page.free') }}
                        @else
                            ${{ number_format((float) ($selectedDueNow ?? 0), 2) }}
                        @endif
                    </span>
                </div>
                @if($hasAnyFreeTrial ?? false)
                    <p class="text-xs text-right text-slate-400 mb-6">
                        {{ __('gemini-landing.checkout_page.due_note_trial') }}
                    </p>
                @else
                    <p class="text-xs text-right text-slate-400 mb-6">
                        {{ __('gemini-landing.checkout_page.due_note', [
                            'amount' => number_format((float) ($selectedDueNow ?? 0), 2),
                            'billing' => (($period ?? 'month') === 'year' ? __('gemini-landing.checkout_page.billing_yearly') : __('gemini-landing.checkout_page.billing_monthly')),
                        ]) }}
                    </p>
                @endif

                <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg flex justify-center gap-4 text-2xl text-slate-400 grayscale opacity-70">
                    <i class="fa-brands fa-cc-visa"></i>
                    <i class="fa-brands fa-cc-mastercard"></i>
                    <i class="fa-brands fa-cc-amex"></i>
                </div>
            </div>
        </div>

    </div>
</main>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.3.2/build/css/intlTelInput.css">
    <style>
        .iti { width: 100%; }
        .iti__country-list { z-index: 70; }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.3.2/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.3.2/build/js/utils.js"></script>

    <script>
        function initCompanyPhoneIntlTel() {
            const phoneInput = document.getElementById('company_phone_iti');
            const hiddenInput = document.getElementById('company_phone_value');
            if (!phoneInput || !hiddenInput) return;
            if (phoneInput.dataset.itiInitialized === '1') return;
            if (!window.intlTelInput) return;

            phoneInput.dataset.itiInitialized = '1';

            const iti = window.intlTelInput(phoneInput, {
                initialCountry: 'sa',
                nationalMode: false,
                autoPlaceholder: 'polite',
                separateDialCode: false,
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.3.2/build/js/utils.js',
            });

            function dispatchHiddenUpdate(value) {
                hiddenInput.value = value;
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            }

            function ensureDialCodePrefilled() {
                const dialCode = iti.getSelectedCountryData()?.dialCode;
                if (!dialCode) return;

                const current = String(phoneInput.value || '').trim();
                const desired = `+${dialCode}`;

                if (current === '' || current === '+' || (/^\+\d+$/.test(current) && current.length <= desired.length)) {
                    phoneInput.value = desired;
                }
            }

            function syncToLivewire() {
                let value = '';
                try {
                    value = iti.getNumber();
                } catch (e) {
                    value = '';
                }

                if (!value) {
                    value = String(phoneInput.value || '').trim();
                }

                dispatchHiddenUpdate(value);
            }

            const initialValue = String(hiddenInput.value || phoneInput.value || '').trim();
            if (initialValue) {
                try {
                    iti.setNumber(initialValue);
                } catch (e) {
                }
            }

            ensureDialCodePrefilled();
            syncToLivewire();

            phoneInput.addEventListener('input', syncToLivewire);
            phoneInput.addEventListener('blur', syncToLivewire);
            phoneInput.addEventListener('countrychange', () => {
                ensureDialCodePrefilled();
                syncToLivewire();
            });

            const countrySelect = document.querySelector('select[wire\\:model\\.live="data.country_id"], select[wire\\:model="data.country_id"]');
            if (countrySelect) {
                const iso3ToIso2 = {
                    sau: 'sa',
                    egy: 'eg',
                    are: 'ae',
                    qat: 'qa',
                    kwt: 'kw',
                    bhr: 'bh',
                    omn: 'om',
                    jor: 'jo',
                    lbn: 'lb',
                    irq: 'iq',
                    yem: 'ye',
                };

                countrySelect.addEventListener('change', () => {
                    const opt = countrySelect.options[countrySelect.selectedIndex];
                    const iso3 = String(opt?.dataset?.code || '').toLowerCase();
                    const byCode = iso3ToIso2[iso3];
                    if (byCode) {
                        iti.setCountry(byCode);
                    } else {
                        const selectedName = String(opt?.text || '').trim().toLowerCase();
                        if (selectedName) {
                            const countryData = window.intlTelInputGlobals?.getCountryData?.() || [];
                            const match = countryData.find(c => String(c.name || '').trim().toLowerCase() === selectedName);
                            if (match?.iso2) iti.setCountry(match.iso2);
                        }
                    }

                    ensureDialCodePrefilled();
                    syncToLivewire();
                });
            }
        }

        document.addEventListener('DOMContentLoaded', initCompanyPhoneIntlTel);
        document.addEventListener('livewire:init', initCompanyPhoneIntlTel);

        if (window.Livewire?.hook) {
            window.Livewire.hook('message.processed', () => {
                initCompanyPhoneIntlTel();
            });
        }
    </script>
@endpush
