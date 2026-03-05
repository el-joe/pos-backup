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

        <div class="flex items-center justify-center mt-12">
            <div class="relative inline-flex p-1.5 bg-slate-100/80 dark:bg-slate-800/60 backdrop-blur-md rounded-full ring-1 ring-slate-200/50 dark:ring-slate-700/50 shadow-inner" role="group" aria-label="{{ __('gemini-landing.pricing_page.billing_frequency_aria') }}">

                <button type="button"
                        wire:click="setBilling('monthly')"
                        aria-pressed="{{ !$this->isYearly() ? 'true' : 'false' }}"
                        class="relative z-10 w-32 py-2.5 text-sm font-bold text-center rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950 {{ !$this->isYearly() ? 'bg-white text-slate-900 shadow-md shadow-slate-200/50 ring-1 ring-slate-200/50 dark:bg-slate-700 dark:text-white dark:shadow-none dark:ring-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">
                    {{ __('gemini-landing.pricing_page.billing_monthly') }}
                </button>

                <button type="button"
                        wire:click="setBilling('yearly')"
                        aria-pressed="{{ $this->isYearly() ? 'true' : 'false' }}"
                        class="relative z-10 flex items-center justify-center w-44 gap-2 py-2.5 text-sm font-bold text-center rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950 {{ $this->isYearly() ? 'bg-white text-slate-900 shadow-md shadow-slate-200/50 ring-1 ring-slate-200/50 dark:bg-slate-700 dark:text-white dark:shadow-none dark:ring-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">
                    {{ __('gemini-landing.pricing_page.billing_yearly') }}
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-black tracking-wider text-emerald-700 uppercase bg-emerald-100/80 rounded-full dark:bg-emerald-500/20 dark:text-emerald-400 shadow-sm border border-emerald-200/50 dark:border-emerald-500/30">
                        {{ __('gemini-landing.pricing_page.save_20') }}
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

                <div role="button"
                     tabindex="0"
                     wire:click="setPlan({{ (int) $plan['id'] }})"
                     wire:keydown.enter.prevent="setPlan({{ (int) $plan['id'] }})"
                     wire:keydown.space.prevent="setPlan({{ (int) $plan['id'] }})"
                     aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                     class="relative flex flex-col w-full text-left transition-all duration-300 bg-white border rounded-2xl dark:bg-slate-900 focus:outline-none {{ $isSelected ? 'border-indigo-600 ring-2 ring-indigo-600 shadow-xl dark:border-indigo-500 dark:ring-indigo-500 scale-[1.02] z-10' : 'border-slate-200 dark:border-slate-800 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md hover:-translate-y-1' }}">

                    @if($isSelected)
                        <div class="absolute inset-x-0 top-0 h-1.5 rounded-t-2xl bg-indigo-600 dark:bg-indigo-500" aria-hidden="true"></div>
                    @endif

                    @if(!empty($plan['recommended']))
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                            <span class="flex items-center gap-1.5 px-4 py-1 text-[11px] font-semibold tracking-wide text-indigo-700 bg-white border border-indigo-200 rounded-full shadow-sm dark:bg-slate-900 dark:border-indigo-500/40 dark:text-indigo-300 whitespace-nowrap">
                                <i class="fa-solid fa-star text-[10px] text-indigo-500 dark:text-indigo-400"></i>
                                {{ __('gemini-landing.pricing_page.most_popular') }}
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-col flex-1 p-8 xl:p-10">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                                {{ $plan['name'] }}
                            </h2>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('gemini-landing.pricing_page.card_subtitle') }}
                            </p>
                        </div>

                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                                ${{ number_format((float) $displayPrice, 0) }}
                            </span>
                            <span class="text-base font-medium text-slate-500 dark:text-slate-400">/{{ $this->isYearly() ? __('gemini-landing.common.period_year') : __('gemini-landing.common.period_month') }}</span>
                        </div>

                        <div class="h-6 mb-6">
                            @if($hasTrialText)
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                    <i class="fa-solid fa-gift text-xs" aria-hidden="true"></i>
                                    {{ __('gemini-landing.pricing_page.free_trial', ['months' => (int) $plan['trial_months']]) }}
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

                        <div class="pt-8">
                            <button type="button"
                                    wire:click.stop="checkoutPlan({{ (int) $plan['id'] }})"
                                    class="group inline-flex items-center justify-center w-full gap-3 px-6 py-4 rounded-xl text-sm font-bold text-white transition-all duration-300 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:shadow-indigo-500/20">
                                <span class="relative z-10 text-white">{{ $hasTrialText ? __('gemini-landing.pricing_page.cta_try_free') : __('gemini-landing.pricing_page.cta_subscribe_now') }}</span>
                                <i class="fa-solid fa-arrow-right relative z-10 text-white transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>
