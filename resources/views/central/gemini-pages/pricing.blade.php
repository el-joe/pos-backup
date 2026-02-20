@extends('layouts.central.gemini.layout')

@section('content')
@php
    $resolvePlanFeatures = function ($plan) {
        return $plan->plan_features
            ->filter(function ($planFeature) {
                if (!$planFeature->feature) return false;
                if ($planFeature->feature->type === 'boolean') return (int) $planFeature->value === 1;
                return ((int) $planFeature->value > 0)
                    || (is_string($planFeature->content_en) && trim($planFeature->content_en) !== '')
                    || (is_string($planFeature->content_ar) && trim($planFeature->content_ar) !== '');
            })
            ->sortBy('feature_id')
            ->map(function ($planFeature) {
                $feature = $planFeature->feature;
                $name = app()->getLocale() === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                return $name ?: ($feature->name_en ?: $feature->code);
            })
            ->unique()
            ->values()
            ->take(3)
            ->all();
    };
@endphp

<style>
    /* Dynamic Pricing Toggle Utility */
    #pricing-wrapper.is-yearly .display-monthly { display: none !important; }
    #pricing-wrapper.is-yearly .display-yearly { display: block !important; }
    #pricing-wrapper.is-monthly .display-monthly { display: block !important; }
    #pricing-wrapper.is-monthly .display-yearly { display: none !important; }
</style>

<main id="pricing-wrapper" class="is-monthly bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased pb-40 min-h-screen font-sans" itemscope itemtype="https://schema.org/WebPage">
    <header class="pt-32 pb-12 text-center px-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-200/50 to-transparent dark:from-slate-900/50 dark:to-transparent -z-10"></div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Build Your Custom Suite</h1>
        <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-10 font-medium leading-relaxed">Select the systems you need and choose the perfect plan for your team. Scale up whenever you're ready.</p>

        <input type="checkbox" id="billing-toggle" class="hidden" onchange="calculateTotal()" />
        <div class="inline-flex items-center p-1.5 bg-white dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm">
            <button id="btn-monthly" class="px-8 py-2.5 rounded-full text-sm font-bold bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white transition-all duration-300 shadow-inner" onclick="setBilling('monthly')">Monthly</button>
            <button id="btn-yearly" class="px-8 py-2.5 rounded-full text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all duration-300 flex items-center gap-2" onclick="setBilling('yearly')">
                Yearly <span class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 text-[10px] px-2.5 py-0.5 rounded-full uppercase tracking-widest font-black shadow-sm">Save 20%</span>
            </button>
        </div>
    </header>

    <section class="container mx-auto max-w-5xl px-6 space-y-8 relative z-10" style="padding-bottom: 40px">

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" id="card-pos">
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer group" onclick="toggleSystem('pos', 'indigo')">
                <div class="flex items-center gap-5">

                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-2xl shadow-[0_8px_16px_-6px_rgba(99,102,241,0.6),inset_0_2px_4px_rgba(255,255,255,0.4)] border border-indigo-300/50 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-boxes-stacked drop-shadow-sm"></i>
                    </div>

                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-1">POS & ERP System</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Inventory, sales, and comprehensive accounting.</p>
                    </div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer mt-2 sm:mt-0" onclick="event.stopPropagation(); toggleSystem('pos', 'indigo')">
                    <div class="w-14 h-8 bg-slate-200 dark:bg-slate-800 rounded-full transition-colors duration-300 shadow-inner border border-slate-300 dark:border-slate-700" id="toggle-bg-pos"></div>
                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 shadow-md" id="toggle-dot-pos"></div>
                </div>
                <input type="checkbox" class="hidden" id="check-pos" onchange="calculateTotal()">
            </div>

            <div id="config-pos" class="hidden border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 p-6 sm:p-8 rounded-b-2xl">
                <div class="flex justify-between items-end mb-6">
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Select Plan Tier</p>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/20 px-3 py-1.5 rounded-md shadow-sm border border-indigo-100 dark:border-indigo-800/30" id="trial-pos" style="display: none;"></p>
                </div>

                @php
                    $posPlans = ($plansByModule['pos'] ?? collect());
                    $posDefault = $posPlans->firstWhere('recommended', true) ?? $posPlans->first();
                @endphp
                <select id="tier-pos" class="hidden" onchange="calculateTotal()">
                    @foreach($posPlans as $plan)
                        @php $trialMonths = (bool) ($plan->three_months_free ?? false) ? 3 : 0; @endphp
                        <option value="{{ $plan->id }}" data-slug="{{ $plan->slug }}" data-month="{{ round((float) $plan->price_month, 2) }}" data-year="{{ round((float) $plan->price_year, 2) }}" data-trial-months="{{ $trialMonths }}" data-plan-name="{{ $plan->name }}" @selected($posDefault && $posDefault->id === $plan->id)>{{ $plan->name }}</option>
                    @endforeach
                </select>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($posPlans as $plan)
                        @php
                            $discountedMonth = round((float) $plan->price_month, 2);
                            $discountedYear = round((float) $plan->price_year, 2);
                            $planFeatureNames = $resolvePlanFeatures($plan);
                        @endphp
                        <button type="button" onclick="setTier('pos', '{{ $plan->id }}', 'indigo')" data-tier-module="pos" data-plan-id="{{ $plan->id }}" class="tier-btn relative bg-white dark:bg-slate-950 rounded-2xl p-6 text-left transition-all duration-300 border-2 border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-600 focus:outline-none flex flex-col h-full shadow-sm hover:shadow-md">
                            <div class="relative z-10 flex flex-col h-full w-full">
                                <div class="font-extrabold text-slate-900 dark:text-white text-lg">{{ $plan->name }}</div>
                                <div class="my-4">
                                    <div class="display-monthly text-4xl font-black text-slate-900 dark:text-white">${{ number_format($discountedMonth, 0) }}<span class="text-sm font-medium text-slate-400">/mo</span></div>
                                    <div class="display-yearly text-4xl font-black text-slate-900 dark:text-white">${{ number_format($discountedYear, 0) }}<span class="text-sm font-medium text-slate-400">/mo</span></div>
                                </div>
                                @if(count($planFeatureNames) > 0)
                                    <div class="mt-auto pt-5 border-t border-slate-100 dark:border-slate-800/80 w-full">
                                        <ul class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-400">
                                            @foreach($planFeatureNames as $featureName)
                                                <li class="flex items-start gap-3"><i class="fa-solid fa-check text-indigo-500 mt-0.5 text-xs"></i> <span>{{ $featureName }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" id="card-hrm">
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer group" onclick="toggleSystem('hrm', 'emerald')">
                <div class="flex items-center gap-5">

                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-2xl shadow-[0_8px_16px_-6px_rgba(16,185,129,0.6),inset_0_2px_4px_rgba(255,255,255,0.4)] border border-emerald-300/50 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-users drop-shadow-sm"></i>
                    </div>

                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-1">HRM System</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Payroll, attendance, and team management.</p>
                    </div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer mt-2 sm:mt-0" onclick="event.stopPropagation(); toggleSystem('hrm', 'emerald')">
                    <div class="w-14 h-8 bg-slate-200 dark:bg-slate-800 rounded-full transition-colors duration-300 shadow-inner border border-slate-300 dark:border-slate-700" id="toggle-bg-hrm"></div>
                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 shadow-md" id="toggle-dot-hrm"></div>
                </div>
                <input type="checkbox" class="hidden" id="check-hrm" onchange="calculateTotal()">
            </div>

            <div id="config-hrm" class="hidden border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 p-6 sm:p-8 rounded-b-2xl">
                <div class="flex justify-between items-end mb-6">
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Select Plan Tier</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-md shadow-sm border border-emerald-100 dark:border-emerald-800/30" id="trial-hrm" style="display: none;"></p>
                </div>

                @php
                    $hrmPlans = ($plansByModule['hrm'] ?? collect());
                    $hrmDefault = $hrmPlans->firstWhere('recommended', true) ?? $hrmPlans->first();
                @endphp
                <select id="tier-hrm" class="hidden" onchange="calculateTotal()">
                    @foreach($hrmPlans as $plan)
                        @php $trialMonths = (bool) ($plan->three_months_free ?? false) ? 3 : 0; @endphp
                        <option value="{{ $plan->id }}" data-slug="{{ $plan->slug }}" data-month="{{ round((float) $plan->price_month, 2) }}" data-year="{{ round((float) $plan->price_year, 2) }}" data-trial-months="{{ $trialMonths }}" data-plan-name="{{ $plan->name }}" @selected($hrmDefault && $hrmDefault->id === $plan->id)>{{ $plan->name }}</option>
                    @endforeach
                </select>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($hrmPlans as $plan)
                        @php
                            $discountedMonth = round((float) $plan->price_month, 2);
                            $discountedYear = round((float) $plan->price_year, 2);
                            $planFeatureNames = $resolvePlanFeatures($plan);
                        @endphp
                        <button type="button" onclick="setTier('hrm', '{{ $plan->id }}', 'emerald')" data-tier-module="hrm" data-plan-id="{{ $plan->id }}" class="tier-btn relative bg-white dark:bg-slate-950 rounded-2xl p-6 text-left transition-all duration-300 border-2 border-slate-200 dark:border-slate-800 hover:border-emerald-300 dark:hover:border-emerald-600 focus:outline-none flex flex-col h-full shadow-sm hover:shadow-md">
                            <div class="relative z-10 flex flex-col h-full w-full">
                                <div class="font-extrabold text-slate-900 dark:text-white text-lg">{{ $plan->name }}</div>
                                <div class="my-4">
                                    <div class="display-monthly text-4xl font-black text-slate-900 dark:text-white">${{ number_format($discountedMonth, 0) }}<span class="text-sm font-medium text-slate-400">/mo</span></div>
                                    <div class="display-yearly text-4xl font-black text-slate-900 dark:text-white">${{ number_format($discountedYear, 0) }}<span class="text-sm font-medium text-slate-400">/mo</span></div>
                                </div>
                                @if(count($planFeatureNames) > 0)
                                    <div class="mt-auto pt-5 border-t border-slate-100 dark:border-slate-800/80 w-full">
                                        <ul class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-400">
                                            @foreach($planFeatureNames as $featureName)
                                                <li class="flex items-start gap-3"><i class="fa-solid fa-check text-emerald-500 mt-0.5 text-xs"></i> <span>{{ $featureName }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" id="card-booking">
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer group" onclick="toggleSystem('booking', 'rose')">
                <div class="flex items-center gap-5">

                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600  flex items-center justify-center text-2xl shadow-[0_8px_16px_-6px_rgba(225,29,72,0.6),inset_0_2px_4px_rgba(255,255,255,0.4)] border border-rose-300/50 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-calendar-check drop-shadow-sm"></i>
                    </div>

                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-1">Booking & Reservations</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Smart scheduling, calendars, and reminders.</p>
                    </div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer mt-2 sm:mt-0" onclick="event.stopPropagation(); toggleSystem('booking', 'rose')">
                    <div class="w-14 h-8 bg-slate-200 dark:bg-slate-800 rounded-full transition-colors duration-300 shadow-inner border border-slate-300 dark:border-slate-700" id="toggle-bg-booking"></div>
                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 shadow-md" id="toggle-dot-booking"></div>
                </div>
                <input type="checkbox" class="hidden" id="check-booking" onchange="calculateTotal()">
            </div>

            <div id="config-booking" class="hidden border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 p-6 sm:p-8 rounded-b-2xl">
                <div class="flex justify-between items-end mb-6">
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Select Plan Tier</p>
                    <p class="text-xs text-rose-600 dark:text-rose-400 font-bold bg-rose-50 dark:bg-rose-900/20 px-3 py-1.5 rounded-md shadow-sm border border-rose-100 dark:border-rose-800/30" id="trial-booking" style="display: none;"></p>
                </div>

                @php
                    $bookingPlans = ($plansByModule['booking'] ?? collect());
                    $bookingDefault = $bookingPlans->firstWhere('recommended', true) ?? $bookingPlans->first();
                @endphp
                <select id="tier-booking" class="hidden" onchange="calculateTotal()">
                    @foreach($bookingPlans as $plan)
                         @php $trialMonths = (bool) ($plan->three_months_free ?? false) ? 3 : 0; @endphp
                        <option value="{{ $plan->id }}" data-slug="{{ $plan->slug }}" data-month="{{ round((float) $plan->price_month, 2) }}" data-year="{{ round((float) $plan->price_year, 2) }}" data-trial-months="{{ $trialMonths }}" data-plan-name="{{ $plan->name }}" @selected($bookingDefault && $bookingDefault->id === $plan->id)>{{ $plan->name }}</option>
                    @endforeach
                </select>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($bookingPlans as $plan)
                        @php
                            $discountedMonth = round((float) $plan->price_month, 2);
                            $discountedYear = round((float) $plan->price_year, 2);
                            $planFeatureNames = $resolvePlanFeatures($plan);
                        @endphp
                        <button type="button" onclick="setTier('booking', '{{ $plan->id }}', 'rose')" data-tier-module="booking" data-plan-id="{{ $plan->id }}" class="tier-btn relative bg-white dark:bg-slate-950 rounded-2xl p-6 text-left transition-all duration-300 border-2 border-slate-200 dark:border-slate-800 hover:border-rose-300 dark:hover:border-rose-600 focus:outline-none flex flex-col h-full shadow-sm hover:shadow-md">
                            <div class="relative z-10 flex flex-col h-full w-full">
                                <div class="font-extrabold text-slate-900 dark:text-white text-lg">{{ $plan->name }}</div>
                                <div class="my-4">
                                    <div class="display-monthly text-4xl font-black text-slate-900 dark:text-white">${{ number_format($discountedMonth, 0) }}<span class="text-sm font-medium text-slate-400">/mo</span></div>
                                    <div class="display-yearly text-4xl font-black text-slate-900 dark:text-white">${{ number_format($discountedYear, 0) }}<span class="text-sm font-medium text-slate-400">/mo</span></div>
                                </div>
                                @if(count($planFeatureNames) > 0)
                                    <div class="mt-auto pt-5 border-t border-slate-100 dark:border-slate-800/80 w-full">
                                        <ul class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-400">
                                            @foreach($planFeatureNames as $featureName)
                                                <li class="flex items-start gap-3"><i class="fa-solid fa-check-circle text-rose-500 mt-0.5"></i> <span>{{ $featureName }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="fixed bottom-0 left-0 right-0 bg-slate-900 dark:bg-slate-950 border-t border-slate-800 p-4 sm:p-5 shadow-[0_-20px_40px_rgba(0,0,0,0.15)] z-50 translate-y-full transition-transform duration-500 ease-out" id="cart-bar">
        <div class="container mx-auto max-w-5xl flex flex-col sm:flex-row justify-between items-center gap-4">

            <div class="flex items-center gap-4">
                <div class="flex flex-col">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Your Selection</span>
                    <div class="flex items-center gap-2 mt-1">
                        <span id="system-count" class="font-black text-2xl text-white">0</span>
                        <span class="text-sm font-medium text-slate-300">Modules Selected</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                <div class="text-right flex flex-col justify-center">
                    <div class="text-[10px] text-slate-400 line-through h-3 font-semibold uppercase tracking-widest" id="original-price"></div>
                    <div class="text-3xl font-black text-white" id="total-price">$0<span class="text-base font-medium text-slate-400">/mo</span></div>
                    <p class="text-xs text-white font-bold h-4 mt-1" id="due-note"></p>
                </div>
                <button id="proceed-to-checkout" class="bg-indigo-500 text-white px-8 py-3.5 rounded-xl font-extrabold hover:bg-indigo-400 transition-all active:scale-95 flex items-center gap-3 shadow-lg shadow-indigo-500/20">
                    Checkout <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const checkoutBase = @json(route('tenant-checkout'));

    function getSelectedPrice(moduleKey, isYearly) {
        const select = document.getElementById('tier-' + moduleKey);
        if (!select || !select.options || select.selectedIndex < 0) return 0;
        const option = select.options[select.selectedIndex];
        return parseFloat(isYearly ? option.dataset.year : option.dataset.month) || 0;
    }

    function getSelectedTrialMonths(moduleKey) {
        const select = document.getElementById('tier-' + moduleKey);
        if (!select || !select.options || select.selectedIndex < 0) return 0;
        return parseInt(select.options[select.selectedIndex].dataset.trialMonths || '0', 10) || 0;
    }

    function getSelectedPlanMeta(moduleKey) {
        const select = document.getElementById('tier-' + moduleKey);
        if (!select || !select.options || select.selectedIndex < 0) return null;
        const option = select.options[select.selectedIndex];
        return {
            module: moduleKey,
            plan_id: option.value,
            slug: option.dataset.slug || '',
            name: option.dataset.planName || option.text || '',
            trial_months: parseInt(option.dataset.trialMonths || '0', 10) || 0,
        };
    }

    function setBilling(period) {
        const billingToggle = document.getElementById('billing-toggle');
        billingToggle.checked = period === 'yearly';

        const wrapper = document.getElementById('pricing-wrapper');
        if(period === 'yearly') {
            wrapper.classList.remove('is-monthly');
            wrapper.classList.add('is-yearly');
        } else {
            wrapper.classList.remove('is-yearly');
            wrapper.classList.add('is-monthly');
        }

        const btnMonthly = document.getElementById('btn-monthly');
        const btnYearly = document.getElementById('btn-yearly');

        if (period === 'monthly') {
            btnMonthly.className = 'px-8 py-2.5 rounded-full text-sm font-bold bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white transition-all duration-300 shadow-inner';
            btnYearly.className = 'px-8 py-2.5 rounded-full text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all duration-300 flex items-center gap-2';
        } else {
            btnYearly.className = 'px-8 py-2.5 rounded-full text-sm font-bold bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white transition-all duration-300 flex items-center gap-2 shadow-inner';
            btnMonthly.className = 'px-8 py-2.5 rounded-full text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all duration-300';
        }

        calculateTotal();
    }

    function setTier(moduleKey, planId, colorTheme) {
        const select = document.getElementById('tier-' + moduleKey);
        if (!select) return;

        const optionIndex = Array.from(select.options).findIndex((opt) => String(opt.value) === String(planId));
        if (optionIndex >= 0) select.selectedIndex = optionIndex;

        // Reset styling for all buttons in this module
        document.querySelectorAll(`[data-tier-module="${moduleKey}"]`).forEach((btn) => {
            btn.className = `tier-btn relative bg-white dark:bg-slate-950 rounded-2xl p-6 text-left transition-all duration-300 border-2 border-slate-200 dark:border-slate-800 hover:border-${colorTheme}-300 dark:hover:border-${colorTheme}-600 focus:outline-none flex flex-col h-full shadow-sm hover:shadow-md`;
        });

        // Apply dynamic theme color border and ring to the active button
        const activeButton = document.querySelector(`[data-tier-module="${moduleKey}"][data-plan-id="${planId}"]`);
        if (activeButton) {
            activeButton.className = `tier-btn relative bg-${colorTheme}-50/30 dark:bg-${colorTheme}-900/10 rounded-2xl p-6 text-left transition-all duration-300 border-2 border-${colorTheme}-500 dark:border-${colorTheme}-400 ring-4 ring-${colorTheme}-500/10 dark:ring-${colorTheme}-400/10 focus:outline-none flex flex-col h-full shadow-md`;
        }

        calculateTotal();
    }

    function toggleSystem(moduleKey, colorTheme) {
        const checkbox = document.getElementById('check-' + moduleKey);
        const config = document.getElementById('config-' + moduleKey);
        const card = document.getElementById('card-' + moduleKey);
        const toggleBg = document.getElementById('toggle-bg-' + moduleKey);
        const toggleDot = document.getElementById('toggle-dot-' + moduleKey);

        if (!checkbox || !config || !card || !toggleBg || !toggleDot) return;

        checkbox.checked = !checkbox.checked;

        if (checkbox.checked) {
            config.classList.remove('hidden');
            card.classList.add(`border-${colorTheme}-300`, `dark:border-${colorTheme}-700`, 'shadow-lg', 'ring-4', `ring-${colorTheme}-500/5`);
            card.classList.remove('border-slate-200', 'dark:border-slate-800', 'shadow-sm');
            toggleBg.classList.add(`bg-${colorTheme}-500`);
            toggleBg.classList.remove('bg-slate-200', 'dark:bg-slate-800');
            toggleDot.style.transform = 'translateX(24px)';
        } else {
            config.classList.add('hidden');
            card.classList.remove(`border-${colorTheme}-300`, `dark:border-${colorTheme}-700`, 'shadow-lg', 'ring-4', `ring-${colorTheme}-500/5`);
            card.classList.add('border-slate-200', 'dark:border-slate-800', 'shadow-sm');
            toggleBg.classList.remove(`bg-${colorTheme}-500`);
            toggleBg.classList.add('bg-slate-200', 'dark:bg-slate-800');
            toggleDot.style.transform = 'translateX(0)';
        }

        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        let payableNow = 0;
        const isYearly = document.getElementById('billing-toggle').checked;
        const cartBar = document.getElementById('cart-bar');
        const selectedModules = ['pos', 'hrm', 'booking'].filter((mod) => document.getElementById('check-' + mod)?.checked);
        const selectedCount = selectedModules.length;
        const hasTrialSelection = selectedModules.some((mod) => getSelectedTrialMonths(mod) > 0);
        const selectedPlansPayload = [];

        ['pos', 'hrm', 'booking'].forEach(mod => {
            const checkbox = document.getElementById('check-' + mod);
            const trialElement = document.getElementById('trial-' + mod);
            const trialMonths = getSelectedTrialMonths(mod);
            const planPrice = getSelectedPrice(mod, isYearly);

            if (trialElement) {
                if(checkbox && checkbox.checked && trialMonths > 0) {
                     trialElement.innerHTML = `<i class="fa-solid fa-gift mr-1"></i> ${trialMonths} Month Free Trial`;
                     trialElement.style.display = 'block';
                } else {
                     trialElement.style.display = 'none';
                }
            }

            if (checkbox && checkbox.checked) {
                total += planPrice;
                payableNow += trialMonths > 0 ? 0 : planPrice;
                const selectedMeta = getSelectedPlanMeta(mod);
                if (selectedMeta) {
                    selectedPlansPayload.push(selectedMeta);
                }
            }
        });

        const originalPriceElement = document.getElementById('original-price');
        const dueNoteElement = document.getElementById('due-note');

        // Logic for updating the dark bottom bar text
        if (selectedCount > 0 && payableNow <= 0) {
            originalPriceElement.textContent = `$${total.toFixed(2)} / ${isYearly ? 'yr' : 'mo'}`;
            document.getElementById('total-price').innerHTML = 'Free<span class="text-sm font-medium text-slate-400"> (due now)</span>';
            if (dueNoteElement) dueNoteElement.textContent = hasTrialSelection ? 'All selected systems are in free trial. Due now is Free.' : '';
        } else {
            originalPriceElement.textContent = hasTrialSelection && selectedCount > 0 ? `$${total.toFixed(2)} / ${isYearly ? 'yr' : 'mo'}` : '';
            document.getElementById('total-price').innerHTML = `$${payableNow.toFixed(2)}<span class="text-base font-medium text-slate-400">/${isYearly ? 'yr' : 'mo'}</span>`;
            if (dueNoteElement) {
                dueNoteElement.textContent = hasTrialSelection && selectedCount > 0
                    ? `Selected trial plans are free now. Pay only non-trial systems.`
                    : (selectedCount > 0 ? 'Total amount due today.' : '');
            }
        }

        document.getElementById('system-count').innerText = selectedCount;

        // Animate solid cart bar from bottom
        if (selectedCount > 0) {
            cartBar.classList.remove('translate-y-full');
        } else {
            cartBar.classList.add('translate-y-full');
        }

        // Base 64 Payload logic
        const proceedBtn = document.getElementById('proceed-to-checkout');
        if (proceedBtn) {
            if (selectedModules.length > 0 && selectedPlansPayload.length > 0) {
                const payload = { period: isYearly ? 'year' : 'month', systems: selectedPlansPayload };
                const encodedPayload = btoa(unescape(encodeURIComponent(JSON.stringify(payload))));
                proceedBtn.dataset.checkoutUrl = `${checkoutBase}/${encodeURIComponent(encodedPayload)}`;
            } else {
                proceedBtn.dataset.checkoutUrl = '';
            }
        }
    }

    document.getElementById('proceed-to-checkout')?.addEventListener('click', () => {
        const url = document.getElementById('proceed-to-checkout')?.dataset.checkoutUrl;
        window.location.href = url ? url : '{{ route('pricing-compare') }}';
    });

    // Initialize Default States & Colors
    ['pos', 'hrm', 'booking'].forEach((moduleKey) => {
        const select = document.getElementById('tier-' + moduleKey);
        const colorMap = { 'pos': 'indigo', 'hrm': 'emerald', 'booking': 'rose' };
        if (select && select.value) setTier(moduleKey, select.value, colorMap[moduleKey]);
    });

    setBilling('monthly');
</script>
@endpush
