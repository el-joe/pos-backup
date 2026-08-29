<main id="pricing-wrapper"
    class="min-h-screen pt-32 pb-16 font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
    itemscope
    itemtype="https://schema.org/WebPage">

    <header class="max-w-4xl px-6 mx-auto mb-20 text-center lg:px-8">
        <div class="flex justify-center mb-6 animate-fade-in-up">
            <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold tracking-widest text-indigo-700 uppercase rounded-full bg-indigo-50 dark:bg-indigo-500/10 dark:text-indigo-400 ring-1 ring-inset ring-indigo-500/20 dark:ring-indigo-500/30">
                <i class="fa-solid fa-bolt text-indigo-500 dark:text-indigo-400"></i>
                {{ __('gemini-landing.pricing_page.badge') }}
            </span>
        </div>

        <h1 class="text-4xl font-black tracking-tight sm:text-6xl text-slate-900 dark:text-white">
            {{ __('gemini-landing.pricing_page.title_before') }}<span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-cyan-500 dark:from-indigo-400 dark:to-cyan-400">{{ __('gemini-landing.pricing_page.title_highlight') }}</span>{{ __('gemini-landing.pricing_page.title_after') }}
        </h1>

        <p class="max-w-2xl mx-auto mt-6 text-lg leading-relaxed text-slate-500 dark:text-slate-400">
            {{ __('gemini-landing.pricing_page.subtitle') }}
        </p>
    </header>

    <section class="container px-6 mx-auto max-w-6xl lg:px-8">
        <div class="grid max-w-2xl grid-cols-1 gap-10 mx-auto mt-8 lg:grid-cols-2 lg:max-w-none items-stretch">
            @foreach($plans as $plan)
            @php
            $isSelected = (int) ($selectedPlanId ?? 0) === (int) $plan['id'];
            $isYearly = ($plan['type'] ?? 'monthly') === 'yearly';
            $displayPrice = $plan['price'] ?? 0;
            @endphp

            <div role="button"
                tabindex="0"
                wire:click="setPlan({{ (int) $plan['id'] }})"
                wire:keydown.enter.prevent="setPlan({{ (int) $plan['id'] }})"
                wire:keydown.space.prevent="setPlan({{ (int) $plan['id'] }})"
                aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                class="relative flex flex-col w-full text-left transition-all duration-300 bg-white border rounded-3xl dark:!bg-slate-900 focus:outline-none {{ $isSelected ? 'border-indigo-600 ring-2 ring-indigo-600 shadow-2xl dark:border-indigo-500 dark:ring-indigo-500 scale-[1.02] z-10' : 'border-slate-200 dark:border-slate-800 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-xl hover:-translate-y-1' }}">

                @if($isSelected)
                <div class="absolute inset-x-0 top-0 h-1.5 rounded-t-3xl bg-indigo-600 dark:bg-indigo-500" aria-hidden="true"></div>
                @endif

                @if(!empty($plan['recommended']))
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                    <span class="flex items-center gap-1.5 px-4 py-1 text-[11px] font-semibold tracking-wide text-indigo-700 bg-white border border-indigo-200 rounded-full shadow-sm dark:!bg-slate-900 dark:border-indigo-500/40 dark:text-indigo-300 whitespace-nowrap">
                        <i class="fa-solid fa-star text-[10px] text-indigo-500 dark:text-indigo-400"></i>
                        {{ __('gemini-landing.pricing_page.most_popular') }}
                    </span>
                </div>
                @endif

                <div class="flex flex-col flex-1 p-10 xl:p-12">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                            {{ $plan['name'] }}
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('gemini-landing.pricing_page.card_subtitle') }}
                        </p>
                    </div>

                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                            ${{ number_format((float) $displayPrice, 0) }}
                        </span>
                        <span class="text-base font-medium text-slate-500 dark:text-slate-400">/{{ $isYearly ? __('gemini-landing.common.period_year') : __('gemini-landing.common.period_month') }}</span>
                    </div>

                    @if(!empty($plan['recommended']))
                    <div class="h-6 mb-6">
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                            <i class="fa-solid fa-tag text-xs" aria-hidden="true"></i>
                            {{ __('gemini-landing.pricing_page.save_15') }}
                        </span>
                    </div>
                    @endif

                    <div class="flex-1 pt-8 border-t border-slate-100 dark:border-slate-800">
                        <ul class="space-y-4 text-sm text-slate-600 dark:text-slate-400">
                            @foreach(($plan['features'] ?? []) as $feature)
                            <li class="flex items-center gap-3">
                                <i class="text-sm fa-solid fa-check text-indigo-600 dark:text-indigo-400" aria-hidden="true"></i>
                                <span class="text-slate-700 dark:text-slate-300">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="pt-8">
                        <button type="button"
                            wire:click.stop="checkoutPlan({{ (int) $plan['id'] }})"
                            class="group inline-flex items-center justify-center w-full gap-3 px-6 py-4 rounded-xl text-sm font-bold text-white transition-all duration-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:shadow-indigo-500/20">
                            <span class="relative z-10 text-white">{{ __('gemini-landing.pricing_page.cta_subscribe_now') }}</span>
                            <i class="fa-solid fa-arrow-right relative z-10 text-white transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true"></i>
                        </button>

                        @if(($plan['free_trial_days'] ?? 0) > 0)
                        <button type="button"
                            wire:click.stop="checkoutPlanTrial({{ (int) $plan['id'] }})"
                            class="inline-flex items-center justify-center w-full gap-2 px-6 py-3 mt-3 rounded-xl text-sm font-semibold text-indigo-600 dark:text-indigo-400 border border-indigo-600 dark:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition-colors duration-300">
                            <i class="fa-solid fa-gift" aria-hidden="true"></i>
                            <span>{{ __('gemini-landing.pricing_page.cta_try_free', ['days' => (int) $plan['free_trial_days']]) }}</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</main>