@extends('layouts.central.gemini.layout')

@section('content')
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

                <form action="#" method="POST" class="space-y-8">

                    <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                        <div class="flex items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                            <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-slate-700 text-brand-500 dark:text-brand-400 flex items-center justify-center font-bold border border-brand-100 dark:border-slate-600">1</div>
                            <h2 class="text-lg font-bold text-brand-dark dark:text-white">{{ __('gemini-landing.checkout_page.company_details') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.company_name') }}</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_company_name') }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.industry') }}</label>
                                <select class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                    <option>{{ __('gemini-landing.checkout_page.industry_retail_pos') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.industry_services') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.industry_manufacturing') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.industry_real_estate') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.industry_other') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.company_size') }}</label>
                                <select class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                    <option>{{ __('gemini-landing.checkout_page.company_size_1_10') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.company_size_11_50') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.company_size_50_plus') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.tax_number_optional') }}</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_tax_number') }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.country') }}</label>
                                <select class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                    <option>{{ __('gemini-landing.checkout_page.country_sa') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.country_eg') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.country_uae') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.country_qa') }}</option>
                                    <option>{{ __('gemini-landing.checkout_page.country_other') }}</option>
                                </select>
                            </div>
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
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_first_name') }}">
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.last_name') }}</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_last_name') }}">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.work_email') }}</label>
                                <input type="email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="{{ __('gemini-landing.checkout_page.ph_work_email') }}">
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.password') }}</label>
                                <input type="password" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="••••••••">
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">{{ __('gemini-landing.checkout_page.confirm_password') }}</label>
                                <input type="password" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="mt-6 flex items-start gap-3">
                            <input type="checkbox" id="terms" class="mt-1 w-5 h-5 text-brand-500 border-slate-300 dark:border-slate-600 rounded focus:ring-brand-500 cursor-pointer bg-white dark:bg-slate-900">
                            <label for="terms" class="text-sm text-slate-500 dark:text-slate-400">
                                {!! __('gemini-landing.checkout_page.terms_html', [
                                    'terms' => '<a href="terms.html" class="text-brand-500 hover:underline">' . __('gemini-landing.checkout_page.terms_of_service') . '</a>',
                                    'privacy' => '<a href="privacy.html" class="text-brand-500 hover:underline">' . __('gemini-landing.checkout_page.privacy_policy') . '</a>',
                                ]) !!}
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold text-lg rounded-xl hover:bg-brand-600 transition shadow-lg shadow-brand-500/30 transform hover:-translate-y-0.5">
                        {{ __('gemini-landing.checkout_page.submit_create_account') }}
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

@endsection
