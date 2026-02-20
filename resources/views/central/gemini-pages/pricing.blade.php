@extends('layouts.central.gemini.layout')

@section('content')
<main class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased pb-32" itemscope itemtype="https://schema.org/WebPage">
    <header class="pt-20 pb-10 text-center px-6">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 tracking-tight">Build Your Custom Suite</h1>
        <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-10">Select the systems you need and choose the perfect plan for your team.</p>

        <input type="checkbox" id="billing-toggle" class="hidden" onchange="calculateTotal()" />
        <div class="inline-flex items-center p-1.5 bg-slate-200/60 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700">
            <button id="btn-monthly" class="px-6 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white transition-all" onclick="setBilling('monthly')">Monthly</button>
            <button id="btn-yearly" class="px-6 py-2 rounded-full text-sm font-bold text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all flex items-center gap-2" onclick="setBilling('yearly')">
                Yearly <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wide">Save 20%</span>
            </button>
        </div>
    </header>

    <section class="container mx-auto max-w-5xl px-6 space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-all duration-300" id="card-pos">
            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors" onclick="toggleSystem('pos')">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">POS & ERP System</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Inventory, sales, and accounting.</p>
                    </div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer" onclick="event.stopPropagation(); toggleSystem('pos')">
                    <div class="w-14 h-8 bg-slate-200 dark:bg-slate-700 rounded-full transition-colors duration-300" id="toggle-bg-pos"></div>
                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 shadow-sm" id="toggle-dot-pos"></div>
                </div>
                <input type="checkbox" class="hidden" id="check-pos" onchange="calculateTotal()">
            </div>

            <div id="config-pos" class="hidden border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 p-6">
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Select your tier:</p>
                @php
                    $posPlans = ($plansByModule['pos'] ?? collect());
                    $posDefault = $posPlans->firstWhere('recommended', true) ?? $posPlans->first();
                @endphp
                <select id="tier-pos" class="hidden" onchange="calculateTotal()">
                    @foreach($posPlans as $plan)
                        @php
                            $planDiscount = max(0, min(100, (float) ($plan->discount_percent ?? 0)));
                            $discountFactor = 1 - ($planDiscount / 100);
                            $discountedMonth = round((float) $plan->price_month * $discountFactor, 2);
                            $discountedYear = round((float) $plan->price_year * $discountFactor, 2);
                            $trialMonths = (int) ($plan->free_trial_months ?? 0);
                        @endphp
                        <option
                            value="{{ $plan->id }}"
                            data-slug="{{ $plan->slug }}"
                            data-month="{{ $discountedMonth }}"
                            data-year="{{ $discountedYear }}"
                            data-multi-discount="{{ (float) ($plan->multi_system_discount_percent ?? 0) }}"
                            data-trial-months="{{ $trialMonths }}"
                            @selected($posDefault && $posDefault->id === $plan->id)
                        >{{ $plan->name }}</option>
                    @endforeach
                </select>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($posPlans as $plan)
                        @php
                            $planDiscount = max(0, min(100, (float) ($plan->discount_percent ?? 0)));
                            $discountFactor = 1 - ($planDiscount / 100);
                            $discountedMonth = round((float) $plan->price_month * $discountFactor, 2);
                            $discountedYear = round((float) $plan->price_year * $discountFactor, 2);
                            $trialMonths = (int) ($plan->free_trial_months ?? 0);
                        @endphp
                        <button type="button" onclick="setTier('pos', '{{ $plan->id }}')" data-tier-module="pos" data-plan-id="{{ $plan->id }}" class="tier-btn border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-xl p-4 text-left hover:border-indigo-300 dark:hover:border-indigo-500 transition-all">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $plan->name }}</div>
                            <div class="text-2xl font-black text-indigo-600 dark:text-indigo-300 my-1">${{ number_format($discountedMonth, 2) }}<span class="text-sm font-medium text-slate-500">/mo</span></div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">${{ number_format($discountedYear, 2) }}/yr · {{ $trialMonths > 0 ? 'Free trial ' . $trialMonths . 'm' : 'No free trial' }}</p>
                        </button>
                    @endforeach
                </div>
                <p class="text-xs text-green-600 dark:text-green-400 font-semibold mt-3" id="trial-pos"></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-all duration-300" id="card-hrm">
            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors" onclick="toggleSystem('hrm')">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">HRM System</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Payroll, attendance, and recruitment.</p>
                    </div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer" onclick="event.stopPropagation(); toggleSystem('hrm')">
                    <div class="w-14 h-8 bg-slate-200 dark:bg-slate-700 rounded-full transition-colors duration-300" id="toggle-bg-hrm"></div>
                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 shadow-sm" id="toggle-dot-hrm"></div>
                </div>
                <input type="checkbox" class="hidden" id="check-hrm" onchange="calculateTotal()">
            </div>

            <div id="config-hrm" class="hidden border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 p-6">
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Select your tier:</p>
                @php
                    $hrmPlans = ($plansByModule['hrm'] ?? collect());
                    $hrmDefault = $hrmPlans->firstWhere('recommended', true) ?? $hrmPlans->first();
                @endphp
                <select id="tier-hrm" class="hidden" onchange="calculateTotal()">
                    @foreach($hrmPlans as $plan)
                        @php
                            $planDiscount = max(0, min(100, (float) ($plan->discount_percent ?? 0)));
                            $discountFactor = 1 - ($planDiscount / 100);
                            $discountedMonth = round((float) $plan->price_month * $discountFactor, 2);
                            $discountedYear = round((float) $plan->price_year * $discountFactor, 2);
                            $trialMonths = (int) ($plan->free_trial_months ?? 0);
                        @endphp
                        <option
                            value="{{ $plan->id }}"
                            data-slug="{{ $plan->slug }}"
                            data-month="{{ $discountedMonth }}"
                            data-year="{{ $discountedYear }}"
                            data-multi-discount="{{ (float) ($plan->multi_system_discount_percent ?? 0) }}"
                            data-trial-months="{{ $trialMonths }}"
                            @selected($hrmDefault && $hrmDefault->id === $plan->id)
                        >{{ $plan->name }}</option>
                    @endforeach
                </select>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($hrmPlans as $plan)
                        @php
                            $planDiscount = max(0, min(100, (float) ($plan->discount_percent ?? 0)));
                            $discountFactor = 1 - ($planDiscount / 100);
                            $discountedMonth = round((float) $plan->price_month * $discountFactor, 2);
                            $discountedYear = round((float) $plan->price_year * $discountFactor, 2);
                            $trialMonths = (int) ($plan->free_trial_months ?? 0);
                        @endphp
                        <button type="button" onclick="setTier('hrm', '{{ $plan->id }}')" data-tier-module="hrm" data-plan-id="{{ $plan->id }}" class="tier-btn border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-xl p-4 text-left hover:border-emerald-300 dark:hover:border-emerald-500 transition-all">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $plan->name }}</div>
                            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-300 my-1">${{ number_format($discountedMonth, 2) }}<span class="text-sm font-medium text-slate-500">/mo</span></div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">${{ number_format($discountedYear, 2) }}/yr · {{ $trialMonths > 0 ? 'Free trial ' . $trialMonths . 'm' : 'No free trial' }}</p>
                        </button>
                    @endforeach
                </div>
                <p class="text-xs text-green-600 dark:text-green-400 font-semibold mt-3" id="trial-hrm"></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-all duration-300" id="card-booking">
            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors" onclick="toggleSystem('booking')">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-300 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Booking & Reservations</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Scheduling, calendars, and reminders.</p>
                    </div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer" onclick="event.stopPropagation(); toggleSystem('booking')">
                    <div class="w-14 h-8 bg-slate-200 dark:bg-slate-700 rounded-full transition-colors duration-300" id="toggle-bg-booking"></div>
                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 shadow-sm" id="toggle-dot-booking"></div>
                </div>
                <input type="checkbox" class="hidden" id="check-booking" onchange="calculateTotal()">
            </div>

            <div id="config-booking" class="hidden border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 p-6">
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Select your tier:</p>
                @php
                    $bookingPlans = ($plansByModule['booking'] ?? collect());
                    $bookingDefault = $bookingPlans->firstWhere('recommended', true) ?? $bookingPlans->first();
                @endphp
                <select id="tier-booking" class="hidden" onchange="calculateTotal()">
                    @foreach($bookingPlans as $plan)
                        @php
                            $planDiscount = max(0, min(100, (float) ($plan->discount_percent ?? 0)));
                            $discountFactor = 1 - ($planDiscount / 100);
                            $discountedMonth = round((float) $plan->price_month * $discountFactor, 2);
                            $discountedYear = round((float) $plan->price_year * $discountFactor, 2);
                            $trialMonths = (int) ($plan->free_trial_months ?? 0);
                        @endphp
                        <option
                            value="{{ $plan->id }}"
                            data-slug="{{ $plan->slug }}"
                            data-month="{{ $discountedMonth }}"
                            data-year="{{ $discountedYear }}"
                            data-multi-discount="{{ (float) ($plan->multi_system_discount_percent ?? 0) }}"
                            data-trial-months="{{ $trialMonths }}"
                            @selected($bookingDefault && $bookingDefault->id === $plan->id)
                        >{{ $plan->name }}</option>
                    @endforeach
                </select>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($bookingPlans as $plan)
                        @php
                            $planDiscount = max(0, min(100, (float) ($plan->discount_percent ?? 0)));
                            $discountFactor = 1 - ($planDiscount / 100);
                            $discountedMonth = round((float) $plan->price_month * $discountFactor, 2);
                            $discountedYear = round((float) $plan->price_year * $discountFactor, 2);
                            $trialMonths = (int) ($plan->free_trial_months ?? 0);
                        @endphp
                        <button type="button" onclick="setTier('booking', '{{ $plan->id }}')" data-tier-module="booking" data-plan-id="{{ $plan->id }}" class="tier-btn border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-xl p-4 text-left hover:border-rose-300 dark:hover:border-rose-500 transition-all">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $plan->name }}</div>
                            <div class="text-2xl font-black text-rose-600 dark:text-rose-300 my-1">${{ number_format($discountedMonth, 2) }}<span class="text-sm font-medium text-slate-500">/mo</span></div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">${{ number_format($discountedYear, 2) }}/yr · {{ $trialMonths > 0 ? 'Free trial ' . $trialMonths . 'm' : 'No free trial' }}</p>
                        </button>
                    @endforeach
                </div>
                <p class="text-xs text-green-600 dark:text-green-400 font-semibold mt-3" id="trial-booking"></p>
            </div>
        </div>
    </section>

    <div class="fixed bottom-0 left-0 right-0 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md border-t border-slate-200 dark:border-slate-700 p-4 shadow-[0_-10px_40px_rgba(0,0,0,0.08)] z-50 translate-y-full transition-transform duration-300" id="cart-bar">
        <div class="container mx-auto max-w-5xl flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex flex-col">
                    <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Selected Systems</span>
                    <div class="flex items-center gap-2">
                        <span id="system-count" class="font-bold text-slate-900 dark:text-white">0</span>
                        <span id="discount-badge" class="hidden bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wide font-bold">Multi-System Discount</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                <div class="text-right">
                    <div class="text-xs text-slate-400 line-through h-4" id="original-price"></div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white" id="total-price">$0<span class="text-sm font-medium text-slate-500">/mo</span></div>
                    <p class="text-xs text-green-600 dark:text-green-400 font-semibold" id="due-note"></p>
                </div>
                <button id="proceed-to-checkout" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-brand-600 hover:shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    Continue <i class="fa-solid fa-arrow-right"></i>
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
        const month = parseFloat(option.dataset.month || '0');
        const year = parseFloat(option.dataset.year || '0');
        return isYearly ? year : month;
    }

    function getSelectedMultiDiscount(moduleKey) {
        const select = document.getElementById('tier-' + moduleKey);
        if (!select || !select.options || select.selectedIndex < 0) return 0;
        const option = select.options[select.selectedIndex];
        return parseFloat(option.dataset.multiDiscount || '0');
    }

    function getSelectedTrialMonths(moduleKey) {
        const select = document.getElementById('tier-' + moduleKey);
        if (!select || !select.options || select.selectedIndex < 0) return 0;
        const option = select.options[select.selectedIndex];
        return parseInt(option.dataset.trialMonths || '0', 10) || 0;
    }

    function setBilling(period) {
        const billingToggle = document.getElementById('billing-toggle');
        billingToggle.checked = period === 'yearly';

        const btnMonthly = document.getElementById('btn-monthly');
        const btnYearly = document.getElementById('btn-yearly');

        if (period === 'monthly') {
            btnMonthly.className = 'px-6 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white transition-all';
            btnYearly.className = 'px-6 py-2 rounded-full text-sm font-bold text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all flex items-center gap-2';
        } else {
            btnYearly.className = 'px-6 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white transition-all flex items-center gap-2';
            btnMonthly.className = 'px-6 py-2 rounded-full text-sm font-bold text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all';
        }

        calculateTotal();
    }

    function setTier(moduleKey, planId) {
        const select = document.getElementById('tier-' + moduleKey);
        if (!select) return;

        const optionIndex = Array.from(select.options).findIndex((opt) => String(opt.value) === String(planId));
        if (optionIndex >= 0) {
            select.selectedIndex = optionIndex;
        }

        document.querySelectorAll(`[data-tier-module="${moduleKey}"]`).forEach((btn) => {
            btn.classList.remove('border-slate-900', 'ring-4', 'ring-slate-900/10');
            btn.classList.add('border-slate-200', 'dark:border-slate-600');
        });

        const activeButton = document.querySelector(`[data-tier-module="${moduleKey}"][data-plan-id="${planId}"]`);
        if (activeButton) {
            activeButton.classList.add('border-slate-900', 'ring-4', 'ring-slate-900/10');
            activeButton.classList.remove('border-slate-200', 'dark:border-slate-600');
        }

        calculateTotal();
    }

    function toggleSystem(moduleKey) {
        const checkbox = document.getElementById('check-' + moduleKey);
        const config = document.getElementById('config-' + moduleKey);
        const card = document.getElementById('card-' + moduleKey);
        const toggleBg = document.getElementById('toggle-bg-' + moduleKey);
        const toggleDot = document.getElementById('toggle-dot-' + moduleKey);

        if (!checkbox || !config || !card || !toggleBg || !toggleDot) return;

        checkbox.checked = !checkbox.checked;

        if (checkbox.checked) {
            config.classList.remove('hidden');
            card.classList.add('border-brand-500', 'shadow-md');
            toggleBg.classList.add('bg-emerald-500');
            toggleBg.classList.remove('bg-slate-200', 'dark:bg-slate-700');
            toggleDot.style.transform = 'translateX(24px)';
        } else {
            config.classList.add('hidden');
            card.classList.remove('border-brand-500', 'shadow-md');
            toggleBg.classList.remove('bg-emerald-500');
            toggleBg.classList.add('bg-slate-200', 'dark:bg-slate-700');
            toggleDot.style.transform = 'translateX(0)';
        }

        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        const isYearly = document.getElementById('billing-toggle').checked;
        const cartBar = document.getElementById('cart-bar');
        const selectedModules = ['pos', 'hrm', 'booking'].filter((mod) => {
            const checkbox = document.getElementById('check-' + mod);
            return checkbox && checkbox.checked;
        });
        const selectedCount = selectedModules.length;
        const hasTrialSelection = selectedModules.some((mod) => getSelectedTrialMonths(mod) > 0);

        ['pos', 'hrm', 'booking'].forEach(mod => {
            const checkbox = document.getElementById('check-' + mod);
            const card = document.getElementById('card-' + mod);
            const trialElement = document.getElementById('trial-' + mod);
            const trialMonths = getSelectedTrialMonths(mod);

            if (trialElement) {
                trialElement.textContent = trialMonths > 0
                    ? `Free trial: ${trialMonths} month(s) — pay nothing during trial`
                    : 'No free trial on selected plan';
            }

            if (checkbox && checkbox.checked) {
                let price = getSelectedPrice(mod, isYearly);
                const multiDiscountPercent = getSelectedMultiDiscount(mod);
                if (selectedCount >= 2 && multiDiscountPercent > 0) {
                    price = price - (price * (multiDiscountPercent / 100));
                }
                total += price;
                if (card) card.classList.add('ring-4', 'ring-brand-500/10');
            } else {
                if (card) card.classList.remove('ring-4', 'ring-brand-500/10');
            }
        });

        const originalPriceElement = document.getElementById('original-price');
        const discountBadge = document.getElementById('discount-badge');
        const dueNoteElement = document.getElementById('due-note');

        if (selectedCount >= 2) {
            discountBadge.classList.remove('hidden');
        } else {
            discountBadge.classList.add('hidden');
        }

        if (hasTrialSelection && selectedCount > 0) {
            originalPriceElement.textContent = `$${total.toFixed(2)} / ${isYearly ? 'yr' : 'mo'}`;
            document.getElementById('total-price').innerHTML = 'Free<span class="text-sm font-medium text-slate-500"> (due now)</span>';
            if (dueNoteElement) {
                dueNoteElement.textContent = 'At least one selected plan has free trial, so due now is Free.';
            }
        } else {
            originalPriceElement.textContent = '';
            document.getElementById('total-price').innerHTML = `$${total.toFixed(2)}<span class="text-sm font-medium text-slate-500">/${isYearly ? 'yr' : 'mo'}</span>`;
            if (dueNoteElement) {
                dueNoteElement.textContent = selectedCount > 0 ? 'No trial selected. Amount is payable now.' : '';
            }
        }

        document.getElementById('system-count').innerText = `${selectedCount} System${selectedCount !== 1 ? 's' : ''}`;

        if (selectedCount > 0) {
            cartBar.classList.remove('translate-y-full');
        } else {
            cartBar.classList.add('translate-y-full');
        }

        const proceedBtn = document.getElementById('proceed-to-checkout');
        if (proceedBtn) {
            if (selectedModules.length > 0) {
                const firstModule = selectedModules[0];
                const select = document.getElementById('tier-' + firstModule);
                const selectedOption = select && select.options ? select.options[select.selectedIndex] : null;
                const planSlug = selectedOption ? (selectedOption.dataset.slug || '') : '';
                const period = isYearly ? 'year' : 'month';
                proceedBtn.dataset.checkoutUrl = `${checkoutBase}?plan=${encodeURIComponent(planSlug)}&period=${period}`;
            } else {
                proceedBtn.dataset.checkoutUrl = '';
            }
        }
    }

    document.getElementById('proceed-to-checkout')?.addEventListener('click', () => {
        const url = document.getElementById('proceed-to-checkout')?.dataset.checkoutUrl;
        if (url) {
            window.location.href = url;
        } else {
            window.location.href = '{{ route('pricing-compare') }}';
        }
    });

    ['pos', 'hrm', 'booking'].forEach((moduleKey) => {
        const select = document.getElementById('tier-' + moduleKey);
        if (select && select.value) {
            setTier(moduleKey, select.value);
        }
    });

    setBilling('monthly');
    calculateTotal();
</script>
@endpush
