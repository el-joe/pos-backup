<div class="space-y-6">
    @php
        $totalDays = $currentSubscription ? carbon($currentSubscription->start_date)->diffInDays(carbon($currentSubscription->end_date)) : 0;
        $usedDays = $currentSubscription ? ceil(carbon($currentSubscription->start_date)->diffInDays(now())) : 0;
        $remainingDays = $currentSubscription ? max($totalDays - $usedDays, 0) : 0;
        $progress = $currentSubscription && $totalDays > 0 ? min(($usedDays / $totalDays) * 100, 100) : 0;
    @endphp

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(18rem,0.8fr)]">
        @if($currentSubscription)
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.subscriptions.active_subscription')" icon="fa fa-crown">
                <div class="space-y-6 p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-brand-600 px-5 py-4 text-white">
                        <div class="flex items-center gap-3">
                            <i class="fa fa-crown"></i>
                            <span class="text-sm font-semibold uppercase tracking-[0.2em]">{{ __('general.pages.subscriptions.active_subscription') }}</span>
                        </div>
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]">{{ ucfirst($currentSubscription->status) }}</span>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.plan') }}</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $currentSubscription->plan?->name }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.price') }}</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ currencyFormat($currentSubscription->price, true) }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.start_date') }}</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($currentSubscription->start_date, true, false) }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.end_date') }}</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($currentSubscription->end_date, true, false) }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                            <span class="text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.subscription_progress') }}</span>
                            <span class="font-semibold text-brand-700 dark:text-brand-300">{{ __('general.pages.subscriptions.days_remaining', ['days' => $remainingDays]) }}</span>
                        </div>
                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if($currentSubscription->canRenew() && adminCan('subscriptions.renew'))
                            <button class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" wire:click="renewSubscription">
                                <i class="fa fa-sync"></i> {{ __('general.pages.subscriptions.renew') }}
                            </button>
                        @endif
                        @if($currentSubscription->canCancel() && adminCan('subscriptions.cancel'))
                            <button class="inline-flex items-center gap-2 rounded-2xl border border-rose-300 bg-white px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/30 dark:bg-slate-900 dark:text-rose-300 dark:hover:bg-rose-500/10" wire:click="cancelSubscription">
                                <i class="fa fa-times"></i> {{ __('general.pages.subscriptions.cancel_and_refund') }}
                            </button>
                        @endif
                    </div>
                </div>
            </x-tenant-tailwind-gemini.table-card>
        @endif

        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.subscriptions.account_balance')" icon="fa fa-wallet">
            <div class="flex h-full min-h-[18rem] flex-col items-center justify-center gap-3 p-5 text-center">
                <p class="text-sm font-medium uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.available_balance') }}</p>
                <p class="text-4xl font-semibold text-brand-700 dark:text-brand-300">{{ currencyFormat($accountBalance, true) }}</p>
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.subscriptions.previous_subscriptions')" icon="fa fa-history">
        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.subscriptions.plan') }}</th>
                            <th>{{ __('general.pages.subscriptions.start_date') }}</th>
                            <th>{{ __('general.pages.subscriptions.end_date') }}</th>
                            <th>{{ __('general.pages.subscriptions.status') }}</th>
                            <th class="text-end">{{ __('general.pages.subscriptions.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptions as $subscription)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $subscription->plan?->name }}</td>
                                <td>{{ dateTimeFormat($subscription->start_date, true, false) }}</td>
                                <td>{{ dateTimeFormat($subscription->end_date, true, false) }}</td>
                                <td>
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ ucfirst($subscription->status) }}</span>
                                </td>
                                <td class="text-end fw-semibold">{{ currencyFormat($subscription->price, true) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.no_previous_subscriptions_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
