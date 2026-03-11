<div>
    @if(adminCan('plans.list'))
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.24em] text-brand-600 dark:text-brand-300">{{ __('general.pages.plans.pricing_page') }}</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ __('general.pages.plans.pricing_page') }}</h1>
            </div>

            <div class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.plans.yearly_billing') }}</span>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="planToggleSwitch" wire:model.live="yearly">
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            @foreach ($plans as $plan)
                <div class="relative overflow-hidden rounded-3xl border {{ $plan->recommended == 1 ? 'border-brand-300 bg-brand-50/50 dark:border-brand-500/40 dark:bg-brand-500/10' : 'border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900' }} p-6 shadow-sm">
                    @if($plan->recommended == 1)
                        <div class="absolute right-5 top-5 rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">Recommended</div>
                    @endif

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{{ __('general.pages.plans.plan_name', ['name' => $plan->name]) }}</p>
                            <div class="mt-3 flex items-end gap-2 text-slate-900 dark:text-white">
                                <span class="text-4xl font-semibold">${{ $plan->{"price_".$period} }}</span>
                                <span class="pb-1 text-sm text-slate-500 dark:text-slate-400">/{{ __('general.pages.plans.period_'.$period) }}</span>
                            </div>
                        </div>
                        <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                            <i class="{{ $plan->icon }}"></i>
                        </span>
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach ($plan->features as $title => $feature)
                            @php $featureEnum = \App\Enums\PlanFeaturesEnum::from($title); @endphp
                            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800/60">
                                <i class="mt-1 {{ $feature['status'] ? 'fa fa-check text-emerald-500' : 'fa fa-times text-slate-300 dark:text-slate-600' }}"></i>
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $featureEnum->label() }}</p>
                                    @if($feature['description'] ?? false)
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $feature['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <a href="#" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl {{ $plan->recommended == 1 ? 'bg-brand-600 text-white hover:bg-brand-700' : 'border border-slate-300 bg-white text-slate-700 hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }} px-5 py-3 text-sm font-semibold transition">
                            {{ __('general.pages.plans.get_started') }} <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
            You do not have permission to view this page.
        </div>
    @endif
</div>

@push('scripts')
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
@endpush
