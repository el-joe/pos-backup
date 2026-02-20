<main id="pricing-wrapper" class="{{ $this->isYearly() ? 'is-yearly' : 'is-monthly' }} bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased pb-40 min-h-screen font-sans" itemscope itemtype="https://schema.org/WebPage">
    <header class="pt-24 pb-16 text-center px-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-200/50 to-transparent dark:from-slate-900/50 dark:to-transparent -z-10"></div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Build Your Custom Suite</h1>
        <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-10 font-medium leading-relaxed">Select the systems you need and choose the perfect plan for your team. Scale up whenever you're ready.</p>

        <div class="inline-flex items-center p-1.5 bg-white dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm">
            <button class="px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 {{ $this->isYearly() ? 'text-slate-500 dark:text-slate-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white shadow-inner' }}" wire:click="setBilling('monthly')">Monthly</button>
            <button class="px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2 {{ $this->isYearly() ? 'bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white shadow-inner' : 'text-slate-500 dark:text-slate-400' }}" wire:click="setBilling('yearly')">
                Yearly <span class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 text-[10px] px-2.5 py-0.5 rounded-full uppercase tracking-widest font-black shadow-sm">Save 20%</span>
            </button>
        </div>
    </header>

    <section class="container mx-auto max-w-5xl px-6 space-y-8 relative z-10">
        @foreach($moduleOrder as $module)
            @php
                $ui = $moduleUi[$module] ?? [];
                $plans = $plansByModule[$module] ?? [];
                $selected = !empty($selectedSystems[$module]);
                $selectedPlan = $this->selectedPlan($module);
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-2xl border shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 {{ $selected ? 'border-brand-500 ring-2 ring-brand-500/10' : 'border-slate-200 dark:border-slate-800' }}" id="card-{{ $module }}">
                <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 cursor-pointer group" wire:click="toggleSystem('{{ $module }}')">
                    <div class="flex items-center gap-5">
                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-slate-700 flex items-center justify-center text-2xl shadow border border-slate-600/50 group-hover:scale-105 transition-transform duration-300">
                            <i class="{{ $ui['icon'] ?? 'fa-solid fa-layer-group' }} drop-shadow-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-1">{{ $ui['title'] ?? strtoupper($module) }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">{{ $ui['description'] ?? '' }}</p>
                        </div>
                    </div>
                    <div class="relative inline-flex items-center cursor-pointer mt-2 sm:mt-0" wire:click.stop="toggleSystem('{{ $module }}')">
                        <div class="w-14 h-8 rounded-full transition-colors duration-300 shadow-inner border {{ $selected ? 'bg-emerald-500 border-emerald-500' : 'bg-slate-200 dark:bg-slate-800 border-slate-300 dark:border-slate-700' }}"></div>
                        <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 shadow-md" style="transform: {{ $selected ? 'translateX(24px)' : 'translateX(0)' }};"></div>
                    </div>
                </div>

                @if($selected)
                    <div class="border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 p-6 sm:p-8 rounded-b-2xl">
                        <div class="flex justify-between items-end mb-6">
                            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Select Plan Tier</p>
                            @if(((int) ($selectedPlan['trial_months'] ?? 0)) > 0)
                                <p class="text-xs text-green-600 dark:text-green-400 font-bold bg-green-50 dark:bg-green-900/20 px-3 py-1.5 rounded-md shadow-sm border border-green-100 dark:border-green-800/30"><i class="fa-solid fa-gift mr-1"></i> {{ (int) $selectedPlan['trial_months'] }} Month Free Trial</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            @foreach($plans as $plan)
                                @php
                                    $isPlanSelected = (int) ($selectedPlans[$module] ?? 0) === (int) $plan['id'];
                                @endphp
                                <button type="button" wire:click="setTier('{{ $module }}', {{ (int) $plan['id'] }})" class="relative bg-white dark:bg-slate-950 rounded-2xl p-6 text-left transition-all duration-300 border-2 focus:outline-none flex flex-col h-full shadow-sm hover:shadow-md {{ $isPlanSelected ? 'border-brand-500 ring-4 ring-brand-500/10' : 'border-slate-200 dark:border-slate-800' }}">
                                    <div class="relative z-10 flex flex-col h-full w-full">
                                        <div class="font-extrabold text-slate-900 dark:text-white text-lg">{{ $plan['name'] }}</div>
                                        <div class="my-4">
                                            <div class="display-monthly text-4xl font-black text-slate-900 dark:text-white">${{ number_format((float) ($plan['month'] ?? 0), 0) }}<span class="text-sm font-medium text-slate-400">/mo</span></div>
                                            <div class="display-yearly text-4xl font-black text-slate-900 dark:text-white">${{ number_format((float) ($plan['year'] ?? 0), 0) }}<span class="text-sm font-medium text-slate-400">/mo</span></div>
                                        </div>
                                        @if(!empty($plan['features']))
                                            <div class="mt-auto pt-5 border-t border-slate-100 dark:border-slate-800/80 w-full">
                                                <ul class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-400">
                                                    @foreach($plan['features'] as $featureName)
                                                        <li class="flex items-start gap-3"><i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i> <span>{{ $featureName }}</span></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </section>

    @php
        $selectedCount = $this->selectedCount();
        $totalPrice = $this->totalPrice();
        $dueNow = $this->dueNow();
        $hasTrial = $this->hasTrialSelection();
        $periodLabel = $this->isYearly() ? 'yr' : 'mo';
    @endphp

    <div class="fixed bottom-0 left-0 right-0 bg-slate-900 dark:bg-slate-950 border-t border-slate-800 p-4 sm:p-5 shadow-[0_-20px_40px_rgba(0,0,0,0.15)] z-50 transition-transform duration-500 ease-out {{ $selectedCount > 0 ? 'translate-y-0' : 'translate-y-full' }}">
        <div class="container mx-auto max-w-5xl flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="flex flex-col">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Your Selection</span>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="font-black text-2xl text-white">{{ $selectedCount }}</span>
                        <span class="text-sm font-medium text-slate-300">Modules Selected</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                <div class="text-right flex flex-col justify-center">
                    <div class="text-[10px] text-slate-400 line-through h-3 font-semibold uppercase tracking-widest">{{ $hasTrial && $selectedCount > 0 ? '$' . number_format($totalPrice, 2) . ' / ' . $periodLabel : '' }}</div>
                    @if($selectedCount > 0 && $dueNow <= 0)
                        <div class="text-3xl font-black text-white">Free<span class="text-base font-medium text-slate-400"> (due now)</span></div>
                    @else
                        <div class="text-3xl font-black text-white">${{ number_format($dueNow, 2) }}<span class="text-base font-medium text-slate-400">/{{ $periodLabel }}</span></div>
                    @endif
                    <p class="text-xs text-white font-bold h-4 mt-1">
                        @if($selectedCount === 0)
                        @elseif($hasTrial && $selectedCount > 0)
                            {{ $dueNow <= 0 ? 'All selected systems are in free trial. Due now is Free.' : 'Selected trial plans are free now. Pay only non-trial systems.' }}
                        @else
                            Total amount due today.
                        @endif
                    </p>
                </div>
                <button wire:click="proceedToCheckout" class="bg-indigo-500 text-white px-8 py-3.5 rounded-xl font-extrabold hover:bg-indigo-400 transition-all active:scale-95 flex items-center gap-3 shadow-lg shadow-indigo-500/20">
                    Checkout <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</main>

@push('styles')
<style>
    #pricing-wrapper.is-yearly .display-monthly { display: none !important; }
    #pricing-wrapper.is-yearly .display-yearly { display: block !important; }
    #pricing-wrapper.is-monthly .display-monthly { display: block !important; }
    #pricing-wrapper.is-monthly .display-yearly { display: none !important; }
</style>
@endpush
