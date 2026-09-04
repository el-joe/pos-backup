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
                    <span class="flex items-center gap-2">
                        @if($currentSubscription->is_trial)
                            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]">{{ __('general.pages.subscriptions.free_trial') }}</span>
                        @endif
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]">{{ ucfirst($currentSubscription->status) }}</span>
                    </span>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.plan') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $currentSubscription->plan?->name }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.price') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $currentSubscription->is_trial ? __('general.pages.subscriptions.free_trial') : currencyFormat($currentSubscription->price, true) }}</p>
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
                    @if(adminCan('subscriptions.renew'))
                    <button class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700" wire:click="openChangePlanPanel">
                        <i class="fa fa-right-left"></i> {{ __('general.pages.subscriptions.renew') }} / {{ __('general.pages.subscriptions.change_plan') }}
                    </button>
                    @endif
                    @if($currentSubscription->canCancel() && adminCan('subscriptions.cancel'))
                    <button class="inline-flex items-center gap-2 rounded-2xl border border-rose-300 bg-white px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/30 dark:!bg-slate-900 dark:text-rose-300 dark:hover:bg-rose-500/10" wire:click="cancelSubscription">
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
                @if(adminCan('subscriptions.renew'))
                <button class="inline-flex items-center gap-2 rounded-xl border border-brand-200 px-4 py-2 text-sm font-semibold text-brand-700 hover:bg-brand-50 dark:border-slate-700 dark:text-brand-300 dark:hover:bg-slate-800" wire:click="openTopUpPanel">
                    <i class="fa fa-plus"></i> Add Balance
                </button>
                @endif
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

    @if($showChangePlanPanel)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:key="change-plan-modal">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                    <i class="fa fa-right-left mr-2"></i>{{ __('general.pages.subscriptions.renew') }} / {{ __('general.pages.subscriptions.change_plan') }}
                </h3>
                <button wire:click="closeChangePlanPanel" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa fa-times text-lg"></i>
                </button>
            </div>

            <div class="space-y-5 px-6 py-5">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('general.pages.subscriptions.plan') }}</label>
                    <select wire:model.live="selectedPlanId" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:!bg-slate-800 dark:text-white">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} — {{ currencyFormat($plan->price, true) }} / {{ $plan->isYearly() ? 'year' : 'month' }}</option>
                        @endforeach
                    </select>
                    @error('selectedPlanId') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                @if($pricingPreview)
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">{{ __('general.pages.subscriptions.total') }}</span>
                    <span class="text-lg font-extrabold text-brand-700 dark:text-brand-300">{{ currencyFormat($pricingPreview['final_price'] ?? 0, true) }}</span>
                </div>
                @endif

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Payment Method</label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border p-3 transition {{ $payFromBalance ? 'border-brand-500 bg-brand-50 dark:bg-slate-800' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:!bg-slate-900' }}">
                            <input type="radio" class="hidden" wire:click="$set('payFromBalance', true)">
                            <i class="fa fa-wallet text-2xl text-brand-500"></i>
                            <span class="text-center text-xs font-bold text-slate-700 dark:text-white">{{ __('general.pages.subscriptions.account_balance') }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ currencyFormat($accountBalance, true) }}</span>
                        </label>
                        @foreach($paymentMethods as $pm)
                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border p-3 transition {{ (!$payFromBalance && $selectedPaymentMethodId == $pm->id) ? 'border-brand-500 bg-brand-50 dark:bg-slate-800' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:!bg-slate-900' }}">
                            <input type="radio" class="hidden" wire:click="$set('selectedPaymentMethodId', {{ $pm->id }})">
                            <i class="fa-solid fa-money-bill-wave text-2xl text-slate-400"></i>
                            <span class="text-center text-xs font-bold text-slate-700 dark:text-white">{{ $pm->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('payFromBalance') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    @error('selectedPaymentMethodId') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                @if(!$payFromBalance && ($selectedPaymentMethod->manual ?? false))
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 dark:border-slate-700 dark:!bg-slate-800">
                    <h4 class="mb-1 text-sm font-bold text-blue-900 dark:text-blue-400">Manual Payment</h4>
                    <p class="mb-3 text-xs text-blue-800 dark:text-slate-400">Please transfer the amount using the details below, then upload the receipt to continue.</p>

                    @php $details = $selectedPaymentMethod->details ?? []; $locale = app()->getLocale(); @endphp
                    @if(is_array($details) && count($details) > 0)
                    <div class="mb-3 space-y-2 rounded-lg border border-blue-50 bg-white p-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        @foreach($details as $row)
                            @php
                                $label = $row['label'][$locale] ?? ($row['label']['en'] ?? ($row['key'] ?? ''));
                                $value = $row['value'][$locale] ?? ($row['value']['en'] ?? '');
                            @endphp
                            <div class="flex justify-between gap-4 border-b border-slate-100 pb-1 last:border-b-0 last:pb-0 dark:border-slate-700">
                                <span class="font-bold">{{ $label }}:</span>
                                <span class="text-right">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                    @endif

                    @if($selectedPaymentMethod->currency && $pricingPreview)
                    <div class="mb-3 flex items-center justify-between rounded-lg border border-blue-50 bg-white p-3 dark:border-slate-700 dark:bg-slate-900">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Amount to transfer</span>
                        <span class="text-lg font-extrabold text-brand-dark dark:text-white">
                            {{ number_format(((float) ($pricingPreview['final_price'] ?? 0)) * (float) $selectedPaymentMethod->currency->conversion_rate, 2) }}
                            {{ $selectedPaymentMethod->currency->code }}
                        </span>
                    </div>
                    @endif

                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Upload Receipt <span class="text-rose-500">*</span></label>
                    <input type="file" wire:model="receiptFile" accept=".pdf,.jpg,.jpeg,.png"
                        class="block w-full rounded-lg border border-slate-200 bg-white p-2 text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:file:bg-slate-700 dark:file:text-brand-400">
                    <div wire:loading wire:target="receiptFile" class="mt-2 text-xs font-medium text-brand-500"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Uploading...</div>
                    @error('receiptFile') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                @endif

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4 dark:border-slate-700">
                <button wire:click="closeChangePlanPanel" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancel</button>
                <button wire:click="processSubscriptionChange" wire:loading.attr="disabled" wire:target="processSubscriptionChange"
                    class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 disabled:opacity-60">
                    <span wire:loading.remove wire:target="processSubscriptionChange"><i class="fa fa-check mr-1"></i> Confirm</span>
                    <span wire:loading wire:target="processSubscriptionChange"><i class="fa fa-spinner fa-spin mr-1"></i> Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($showTopUpPanel)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:key="topup-modal">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white"><i class="fa fa-wallet mr-2"></i>Add Balance</h3>
                <button wire:click="closeTopUpPanel" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa fa-times text-lg"></i>
                </button>
            </div>

            <div class="space-y-5 px-6 py-5">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Amount (USD)</label>
                    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 dark:border-slate-700 dark:!bg-slate-800">
                        <span class="text-slate-400">$</span>
                        <input type="number" step="0.01" min="1" wire:model.live="topUpAmount" placeholder="e.g. 50"
                            class="w-full border-0 bg-transparent p-0 text-sm text-slate-900 focus:outline-none focus:ring-0 dark:text-white">
                    </div>
                    @error('topUpAmount') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Payment Method</label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($paymentMethods as $pm)
                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border p-3 transition {{ $topUpPaymentMethodId == $pm->id ? 'border-brand-500 bg-brand-50 dark:bg-slate-800' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:!bg-slate-900' }}">
                            <input type="radio" class="hidden" wire:click="$set('topUpPaymentMethodId', {{ $pm->id }})">
                            <i class="fa-solid fa-money-bill-wave text-2xl text-slate-400"></i>
                            <span class="text-center text-xs font-bold text-slate-700 dark:text-white">{{ $pm->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('topUpPaymentMethodId') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                @if($topUpPaymentMethod->manual ?? false)
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 dark:border-slate-700 dark:!bg-slate-800">
                    <h4 class="mb-1 text-sm font-bold text-blue-900 dark:text-blue-400">Manual Payment</h4>
                    <p class="mb-3 text-xs text-blue-800 dark:text-slate-400">Please transfer the amount using the details below, then upload the receipt to continue.</p>

                    @php $details = $topUpPaymentMethod->details ?? []; $locale = app()->getLocale(); @endphp
                    @if(is_array($details) && count($details) > 0)
                    <div class="mb-3 space-y-2 rounded-lg border border-blue-50 bg-white p-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        @foreach($details as $row)
                            @php
                                $label = $row['label'][$locale] ?? ($row['label']['en'] ?? ($row['key'] ?? ''));
                                $value = $row['value'][$locale] ?? ($row['value']['en'] ?? '');
                            @endphp
                            <div class="flex justify-between gap-4 border-b border-slate-100 pb-1 last:border-b-0 last:pb-0 dark:border-slate-700">
                                <span class="font-bold">{{ $label }}:</span>
                                <span class="text-right">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                    @endif

                    @if($topUpPaymentMethod->currency && $topUpAmount)
                    <div class="mb-3 flex items-center justify-between rounded-lg border border-blue-50 bg-white p-3 dark:border-slate-700 dark:bg-slate-900">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Amount to transfer</span>
                        <span class="text-lg font-extrabold text-brand-dark dark:text-white">
                            {{ number_format(((float) $topUpAmount) * (float) $topUpPaymentMethod->currency->conversion_rate, 2) }}
                            {{ $topUpPaymentMethod->currency->code }}
                        </span>
                    </div>
                    @endif

                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Upload Receipt <span class="text-rose-500">*</span></label>
                    <input type="file" wire:model="topUpReceiptFile" accept=".pdf,.jpg,.jpeg,.png"
                        class="block w-full rounded-lg border border-slate-200 bg-white p-2 text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:file:bg-slate-700 dark:file:text-brand-400">
                    <div wire:loading wire:target="topUpReceiptFile" class="mt-2 text-xs font-medium text-brand-500"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Uploading...</div>
                    @error('topUpReceiptFile') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                @endif

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4 dark:border-slate-700">
                <button wire:click="closeTopUpPanel" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancel</button>
                <button wire:click="processTopUp" wire:loading.attr="disabled" wire:target="processTopUp"
                    class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 disabled:opacity-60">
                    <span wire:loading.remove wire:target="processTopUp"><i class="fa fa-check mr-1"></i> Confirm</span>
                    <span wire:loading wire:target="processTopUp"><i class="fa fa-spinner fa-spin mr-1"></i> Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>