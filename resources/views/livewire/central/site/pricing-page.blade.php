<main id="pricing-wrapper"
    class="min-h-screen pt-20 pb-48 font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
    itemscope
    itemtype="https://schema.org/WebPage">

    <header class="max-w-3xl px-6 mx-auto mb-20 text-center lg:px-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 mb-5 text-xs font-semibold tracking-wide text-indigo-700 uppercase bg-indigo-50 border border-indigo-100 rounded-full dark:bg-indigo-500/10 dark:border-indigo-500/20 dark:text-indigo-300">
            <i class="text-[10px] fa-solid fa-bolt" aria-hidden="true"></i>
            Transparent Pricing
        </span>
        <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl text-slate-900 dark:text-white">
            Simple, transparent pricing
        </h1>
        <p class="max-w-xl mx-auto mt-4 text-lg text-slate-600 dark:text-slate-400">
            Choose the plan that best fits your needs. All plans include our core feature set with values that scale.
        </p>
    </header>

    <section class="px-6 mx-auto max-w-6xl lg:px-8">
        <div class="grid max-w-2xl grid-cols-1 gap-10 mx-auto mt-8 lg:grid-cols-2 lg:max-w-none items-stretch">
            @foreach($plans as $plan)
            @php
            $isSelected = (int) ($selectedPlanId ?? 0) === (int) $plan['id'];
            $displayPrice = ($plan['type'] ?? 'monthly') === 'yearly'
                ? round(($plan['price'] ?? 0) / 12, 2)
                : ($plan['price'] ?? 0);
            $hasTrialText = ((int) ($plan['trial_months'] ?? 0)) > 0;
            @endphp

            <button type="button"
                wire:click="setPlan({{ (int) $plan['id'] }})"
                aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                class="relative flex flex-col w-full text-left transition-all duration-300 bg-white border rounded-3xl dark:!bg-slate-900 focus:outline-none {{ $isSelected ? 'border-indigo-600 ring-2 ring-indigo-600 shadow-2xl dark:border-indigo-500 dark:ring-indigo-500 scale-[1.02] z-10' : 'border-slate-200 dark:border-slate-800 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-xl hover:-translate-y-1' }}">

                @if($isSelected)
                <div class="absolute inset-x-0 top-0 h-1.5 rounded-t-3xl bg-indigo-600 dark:bg-indigo-500" aria-hidden="true"></div>
                @endif

                @if(!empty($plan['recommended']))
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                    <span class="flex items-center gap-1.5 px-4 py-1 text-[11px] font-semibold tracking-wide text-indigo-700 bg-white border border-indigo-200 rounded-full shadow-sm dark:!bg-slate-900 dark:border-indigo-500/40 dark:text-indigo-300 whitespace-nowrap">
                        <i class="fa-solid fa-star text-[10px] text-indigo-500 dark:text-indigo-400"></i>
                        Most Popular
                    </span>
                </div>
                @endif

                <div class="flex flex-col flex-1 p-10 xl:p-12">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                            {{ $plan['name'] }}
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            Best for expanding your business and scaling operations.
                        </p>
                    </div>

                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                            ${{ number_format((float) $displayPrice, 0) }}
                        </span>
                        <span class="text-base font-medium text-slate-500 dark:text-slate-400">/mo</span>
                    </div>

                    <div class="h-6 mb-6">
                        @if($hasTrialText)
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                            <i class="fa-solid fa-gift text-xs" aria-hidden="true"></i>
                            {{ (int) $plan['trial_months'] }} month free trial
                        </span>
                        @endif
                    </div>

                    <div class="flex-1 pt-8 border-t border-slate-100 dark:border-slate-800">
                        <ul class="space-y-4 text-sm text-slate-600 dark:text-slate-400">
                            @foreach(($plan['features'] ?? []) as $feature)
                            @if(($feature['type'] ?? 'boolean') === 'text')
                            <li class="flex items-center justify-between gap-4">
                                <span class="text-slate-600 dark:text-slate-400">{{ $feature['name'] }}</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $feature['display_value'] ?? '—' }}</span>
                            </li>
                            @else
                            <li class="flex items-center justify-between gap-4">
                                <span class="{{ !empty($feature['enabled']) ? 'text-slate-700 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500 line-through decoration-slate-300 dark:decoration-slate-700' }}">
                                    {{ $feature['name'] }}
                                </span>
                                <i class="text-sm fa-solid {{ !empty($feature['enabled']) ? 'fa-check text-indigo-600 dark:text-indigo-400' : 'fa-xmark text-slate-300 dark:text-slate-700' }}" aria-hidden="true"></i>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </button>
            @endforeach
        </div>
    </section>

    @php
    $selectedCount = $this->selectedCount();
    $totalPrice = $this->totalPrice();
    $dueNow = $this->dueNow();
    $hasTrial = $this->hasTrialSelection();
    $periodLabel = 'mo';

    $displayTotalPrice = $totalPrice;
    $displayDueNow = $dueNow;
    @endphp

    @if($selectedCount > 0)
    <section class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 dark:!bg-slate-900 dark:border-slate-800 shadow-[0_-10px_30px_rgba(0,0,0,0.08)] animate-fade-in-up">

        <div class="px-6 py-5 mx-auto max-w-7xl lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-4">
                    <div class="items-center justify-center hidden w-12 h-12 text-indigo-600 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 dark:text-indigo-400 sm:flex">
                        <i class="text-xl fa-solid fa-cube"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold tracking-wide uppercase text-slate-500 dark:text-slate-400">
                            Order Summary
                        </h3>
                        <p class="mt-0.5 text-xl font-bold text-slate-900 dark:text-white">
                            {{ $this->selectedPlan()['name'] ?? '-' }} Plan
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                    <div class="flex flex-row items-center justify-between text-left sm:text-right sm:flex-col sm:items-end">

                        <div>
                            @if($hasTrial && $selectedCount > 0)
                            <div class="hidden text-xs font-medium line-through mb-0.5 text-slate-400 dark:text-slate-500 sm:block whitespace-nowrap">
                                ${{ number_format($displayTotalPrice, 2) }} / {{ $periodLabel }}
                            </div>
                            @endif

                            <div class="text-2xl font-extrabold tracking-tight sm:text-3xl text-slate-900 dark:text-white">
                                @if($selectedCount > 0 && $displayDueNow <= 0)
                                    $0.00
                                    @else
                                    ${{ number_format($displayDueNow, 2) }}<span class="text-lg font-medium text-slate-500 dark:text-slate-400">/{{ $periodLabel }}</span>
                                    @endif
                            </div>
                        </div>

                        <p class="mt-0.5 text-xs font-medium sm:text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            @if($hasTrial)
                            {{ $displayDueNow <= 0 ? 'Due today (Trial active)' : 'Pay remaining due now' }}
                            @else
                            Total due today
                            @endif
                        </p>
                    </div>

                    <button wire:click="proceedToCheckout"
                        class="inline-flex items-center justify-center w-full gap-2 px-8 py-3.5 text-sm font-semibold text-white transition-colors bg-indigo-600 shadow-sm rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 dark:focus:ring-offset-slate-900 sm:w-auto">
                        Continue
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center gap-2 px-6 py-2.5 text-xs border-t bg-slate-50 dark:bg-slate-950/50 border-slate-100 dark:border-slate-800 sm:px-8 text-slate-500 dark:text-slate-400">
            <i class="fa-solid fa-lock text-slate-400"></i>
            Secure, encrypted checkout. You can cancel at any time.
        </div>
    </section>
    @endif
</main>