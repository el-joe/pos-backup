<main id="pricing-wrapper"
      class="min-h-screen pt-32 pb-16 font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
      itemscope
      itemtype="https://schema.org/WebPage">

    <header class="max-w-4xl px-6 mx-auto mb-20 text-center lg:px-8">
        <div class="flex justify-center mb-6 animate-fade-in-up">
            <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold tracking-widest text-indigo-700 uppercase rounded-full bg-indigo-50 dark:bg-indigo-500/10 dark:text-indigo-400 ring-1 ring-inset ring-indigo-500/20 dark:ring-indigo-500/30">
                <i class="fa-solid fa-bolt text-indigo-500 dark:text-indigo-400"></i>
                Pricing Plans
            </span>
        </div>

        <h1 class="text-4xl font-black tracking-tight sm:text-6xl text-slate-900 dark:text-white">
            Simple, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-cyan-500 dark:from-indigo-400 dark:to-cyan-400">transparent</span> pricing
        </h1>

        <p class="max-w-2xl mx-auto mt-6 text-lg leading-relaxed text-slate-500 dark:text-slate-400">
            Choose the plan that best fits your needs. All plans include our core feature set with values that scale effortlessly as you grow.
        </p>

        <div class="flex items-center justify-center mt-12">
            <div class="relative inline-flex p-1.5 bg-slate-100/80 dark:bg-slate-800/60 backdrop-blur-md rounded-full ring-1 ring-slate-200/50 dark:ring-slate-700/50 shadow-inner" role="group" aria-label="Billing frequency">

                <button type="button"
                        wire:click="setBilling('monthly')"
                        aria-pressed="{{ !$this->isYearly() ? 'true' : 'false' }}"
                        class="relative z-10 w-32 py-2.5 text-sm font-bold text-center rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950 {{ !$this->isYearly() ? 'bg-white text-slate-900 shadow-md shadow-slate-200/50 ring-1 ring-slate-200/50 dark:bg-slate-700 dark:text-white dark:shadow-none dark:ring-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">
                    Monthly
                </button>

                <button type="button"
                        wire:click="setBilling('yearly')"
                        aria-pressed="{{ $this->isYearly() ? 'true' : 'false' }}"
                        class="relative z-10 flex items-center justify-center w-44 gap-2 py-2.5 text-sm font-bold text-center rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950 {{ $this->isYearly() ? 'bg-white text-slate-900 shadow-md shadow-slate-200/50 ring-1 ring-slate-200/50 dark:bg-slate-700 dark:text-white dark:shadow-none dark:ring-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">
                    Yearly
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-black tracking-wider text-emerald-700 uppercase bg-emerald-100/80 rounded-full dark:bg-emerald-500/20 dark:text-emerald-400 shadow-sm border border-emerald-200/50 dark:border-emerald-500/30">
                        Save 20%
                    </span>
                </button>

            </div>
        </div>
    </header>

    <section class="container px-6 mx-auto max-w-7xl lg:px-8">
        <div class="grid grid-cols-1 gap-8 mx-auto mt-8 lg:grid-cols-3 max-w-md lg:max-w-none items-stretch">
            @foreach($plans as $plan)
                @php
                    $isSelected = (int) ($selectedPlanId ?? 0) === (int) $plan['id'];
                    $displayPrice = $this->isYearly() ? ($plan['year'] ?? 0) : ($plan['month'] ?? 0);
                    $hasTrialText = ((int) ($plan['trial_months'] ?? 0)) > 0;
                @endphp

                <button type="button"
                        wire:click="setPlan({{ (int) $plan['id'] }})"
                        aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                        class="relative flex flex-col w-full text-left transition-all duration-300 bg-white border rounded-2xl dark:bg-slate-900 focus:outline-none {{ $isSelected ? 'border-indigo-600 ring-2 ring-indigo-600 shadow-xl dark:border-indigo-500 dark:ring-indigo-500 scale-[1.02] z-10' : 'border-slate-200 dark:border-slate-800 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md hover:-translate-y-1' }}">

                    @if($isSelected)
                        <div class="absolute inset-x-0 top-0 h-1.5 rounded-t-2xl bg-indigo-600 dark:bg-indigo-500" aria-hidden="true"></div>
                    @endif

                    @if(!empty($plan['recommended']))
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                            <span class="flex items-center gap-1.5 px-4 py-1 text-[11px] font-semibold tracking-wide text-indigo-700 bg-white border border-indigo-200 rounded-full shadow-sm dark:bg-slate-900 dark:border-indigo-500/40 dark:text-indigo-300 whitespace-nowrap">
                                <i class="fa-solid fa-star text-[10px] text-indigo-500 dark:text-indigo-400"></i>
                                Most Popular
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-col flex-1 p-8 xl:p-10">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                                {{ $plan['name'] }}
                            </h2>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Best for expanding your business and scaling operations.
                            </p>
                        </div>

                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                                ${{ number_format((float) $displayPrice, 0) }}
                            </span>
                            <span class="text-base font-medium text-slate-500 dark:text-slate-400">/{{ $this->isYearly() ? 'year' : 'mo' }}</span>
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
        $isYearly = $this->isYearly();

        // If yearly is selected, multiply the monthly-equivalent price by 12
        $multiplier = $isYearly ? 1 : 1;
        $periodLabel = $isYearly ? 'year' : 'mo';

        $displayTotalPrice = $totalPrice * $multiplier;
        $displayDueNow = $dueNow * $multiplier;
    @endphp

    @if($selectedCount > 0)
        <section class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-t border-slate-200 dark:bg-slate-900/95 dark:border-slate-800 shadow-[0_-15px_40px_-15px_rgba(0,0,0,0.1)] dark:shadow-[0_-15px_40px_-15px_rgba(0,0,0,0.5)] animate-fade-in-up">
            <div class="px-6 py-5 mx-auto max-w-7xl lg:px-8">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">

                    <div class="flex items-center gap-5">
                        <div class="items-center justify-center hidden w-14 h-14 text-white rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 sm:flex shadow-inner shadow-white/20 ring-1 ring-inset ring-indigo-400/50 dark:from-indigo-600 dark:to-indigo-800 dark:ring-indigo-500/30">
                            <i class="text-2xl text-white fa-solid fa-cube drop-shadow-sm"></i>
                        </div>
                        <div>
                            <h3 class="flex items-center gap-2 text-[11px] font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">
                                Order Summary
                            </h3>
                            <p class="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                                {{ $this->selectedPlan()['name'] ?? '-' }} <span class="text-indigo-600 dark:text-indigo-400 font-extrabold">Plan</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:gap-8">
                        <div class="flex flex-row items-center justify-between text-left sm:text-right sm:flex-col sm:items-end">

                            <div class="relative">
                                @if($hasTrial && $selectedCount > 0)
                                    <div class="hidden text-xs font-semibold line-through mb-1 text-slate-500 dark:text-slate-400 sm:block whitespace-nowrap text-right">
                                        ${{ number_format($displayTotalPrice, 2) }} / {{ $periodLabel }}
                                    </div>
                                @endif

                                <div class="text-3xl font-black tracking-tighter sm:text-4xl text-slate-900 dark:text-white">
                                    @if($selectedCount > 0 && $displayDueNow <= 0)
                                        <span class="text-emerald-600 dark:text-emerald-400">Free</span>
                                    @else
                                        ${{ number_format($displayDueNow, 2) }}<span class="text-lg font-bold text-slate-500 dark:text-slate-400">/{{ $periodLabel }}</span>
                                    @endif
                                </div>
                            </div>

                            <p class="mt-1 text-xs font-bold sm:text-sm text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                @if($hasTrial)
                                    <span class="inline-flex items-center gap-1 text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-500/10 px-1.5 py-0.5 rounded text-[10px] uppercase tracking-wider mr-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Trial Active
                                    </span>
                                    {{ $displayDueNow <= 0 ? 'Due today' : 'Pay remaining due now' }}
                                @else
                                    Total due today
                                @endif
                            </p>
                        </div>

                        <button wire:click="proceedToCheckout"
                                class="group relative inline-flex items-center justify-center w-full sm:w-auto gap-3 px-8 py-4 overflow-hidden rounded-xl text-sm font-bold text-white transition-all duration-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:shadow-indigo-500/20">

                            <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-shimmer"></div>

                            <span class="relative z-10 text-white">Continue</span>
                            <i class="fa-solid fa-arrow-right relative z-10 text-white transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center gap-2 px-6 py-3 text-[11px] font-semibold tracking-wide border-t bg-slate-100 dark:bg-slate-950/80 border-slate-200 dark:border-slate-800/80 sm:px-8 text-slate-600 dark:text-slate-400 uppercase">
                <i class="fa-solid fa-lock text-emerald-600 dark:text-emerald-400"></i>
                Secure, encrypted checkout. You can cancel at any time.
            </div>

        </section>

        <style>
            @keyframes shimmer {
                100% { transform: translateX(100%); }
            }
            .animate-shimmer {
                animation: shimmer 1.5s infinite;
            }
        </style>
    @endif
</main>
