@php
    $summaryRows = [
        ['label' => __('general.pages.cash_register.opening_balance'), 'value' => $aggregates['opening_balance'] ?? 0],
        ['label' => __('general.pages.cash_register.total_sales'), 'value' => $aggregates['total_sales'] ?? 0],
        ['label' => __('general.pages.cash_register.total_sale_refunds'), 'value' => $aggregates['total_sale_refunds'] ?? 0],
        ['label' => __('general.pages.cash_register.total_purchases'), 'value' => $aggregates['total_purchases'] ?? 0],
        ['label' => __('general.pages.cash_register.total_purchase_refunds'), 'value' => $aggregates['total_purchase_refunds'] ?? 0],
        ['label' => __('general.pages.cash_register.total_expenses'), 'value' => $aggregates['total_expenses'] ?? 0],
        ['label' => __('general.pages.cash_register.total_expense_refunds'), 'value' => $aggregates['total_expense_refunds'] ?? 0],
        ['label' => __('general.pages.cash_register.total_deposits'), 'value' => $aggregates['total_deposits'] ?? 0],
        ['label' => __('general.pages.cash_register.total_withdrawals'), 'value' => $aggregates['total_withdrawals'] ?? 0],
    ];

    $highlightCards = [
        [
            'label' => __('general.pages.cash_register.opening_balance'),
            'value' => currencyFormat($aggregates['opening_balance'] ?? 0, true),
            'icon' => 'fa fa-wallet',
            'tone' => 'from-emerald-500/15 to-emerald-500/5 text-emerald-600 dark:text-emerald-300',
        ],
        [
            'label' => __('general.pages.cash_register.total_sales'),
            'value' => currencyFormat($aggregates['total_sales'] ?? 0, true),
            'icon' => 'fa fa-arrow-trend-up',
            'tone' => 'from-blue-500/15 to-blue-500/5 text-blue-600 dark:text-blue-300',
        ],
        [
            'label' => __('general.pages.cash_register.total_expenses'),
            'value' => currencyFormat($aggregates['total_expenses'] ?? 0, true),
            'icon' => 'fa fa-receipt',
            'tone' => 'from-amber-500/15 to-amber-500/5 text-amber-600 dark:text-amber-300',
        ],
        [
            'label' => __('general.pages.cash_register.closing_balance'),
            'value' => currencyFormat($aggregates['calculated_closing_balance'] ?? 0, true),
            'icon' => 'fa fa-vault',
            'tone' => 'from-violet-500/15 to-violet-500/5 text-violet-600 dark:text-violet-300',
        ],
    ];
@endphp

@if(adminCan('cash_register.create'))
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach($highlightCards as $card)
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="bg-gradient-to-br {{ $card['tone'] }} p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                                <div class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ $card['value'] }}</div>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/80 text-lg shadow-sm dark:bg-slate-950/60">
                                <i class="{{ $card['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.45fr_minmax(340px,0.9fr)]">
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.cash_register.summary')" :description="__('general.pages.cash_register.aggregated_totals_across_registers')">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-950/60">
                        <tr>
                            <th class="px-5 py-4 text-start text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.field') }}</th>
                            <th class="px-5 py-4 text-end text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($summaryRows as $row)
                            <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                <td class="px-5 py-4 font-medium text-slate-700 dark:text-slate-200">{{ $row['label'] }}</td>
                                <td class="px-5 py-4 text-end text-slate-600 dark:text-slate-300">{{ currencyFormat($row['value'], true) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-slate-50 dark:bg-slate-950/60">
                            <td class="px-5 py-4 text-sm font-bold text-slate-900 dark:text-white">{{ __('general.pages.cash_register.closing_balance') }}</td>
                            <td class="px-5 py-4 text-end text-sm font-bold text-slate-900 dark:text-white">{{ currencyFormat($aggregates['calculated_closing_balance'] ?? 0, true) }}</td>
                        </tr>
                    </tbody>
                </table>
            </x-tenant-tailwind-gemini.table-card>

            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.cash_register.open_close_register')">
                <div class="space-y-5 p-5">
                    @if($currentRegister)
                        <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-brand-700 p-5 text-white">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-white/60">{{ __('general.pages.cash_register.open_close_register') }}</div>
                                    <div class="mt-2 text-xl font-bold">{{ __('general.pages.cash_register.open_since') }}</div>
                                    <div class="mt-1 text-sm text-white/75">{{ dateTimeFormat($currentRegister->opened_at) }}</div>
                                </div>
                                <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]">Open</span>
                            </div>
                            <div class="mt-5 flex items-end justify-between gap-3">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.18em] text-white/60">{{ __('general.pages.cash_register.opening_balance') }}</div>
                                    <div class="mt-2 text-2xl font-black">{{ currencyFormat($currentRegister->opening_balance, true) }}</div>
                                </div>
                                <div class="rounded-2xl bg-white/10 px-4 py-3 text-sm text-white/80">
                                    {{ $currentRegister->branch?->name ?? admin()?->branch?->name ?? __('general.pages.cash_register.select_branch') }}
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                                <div class="mb-4 flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300"><i class="fa fa-arrow-down"></i></span>
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('general.pages.cash_register.cash_deposit') }}</h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.record_deposit') }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.amount') }}</label>
                                        <input type="number" step="any" min="0" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-all focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-blue-500" wire:model="deposit_amount_input">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.notes') }}</label>
                                        <textarea rows="2" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-all focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-blue-500" wire:model="deposit_notes"></textarea>
                                    </div>
                                    <button wire:click="depositCash" class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">{{ __('general.pages.cash_register.record_deposit') }}</button>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                                <div class="mb-4 flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300"><i class="fa fa-arrow-up"></i></span>
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('general.pages.cash_register.cash_withdrawal') }}</h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.record_withdrawal') }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.amount') }}</label>
                                        <input type="number" step="any" min="0" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-all focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-amber-500" wire:model="withdrawal_amount_input">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.notes') }}</label>
                                        <textarea rows="2" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-all focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-amber-500" wire:model="withdrawal_notes"></textarea>
                                    </div>
                                    <button wire:click="withdrawCash" class="inline-flex w-full items-center justify-center rounded-2xl bg-amber-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">{{ __('general.pages.cash_register.record_withdrawal') }}</button>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-rose-200 bg-rose-50/70 p-4 dark:border-rose-500/20 dark:bg-rose-500/10">
                                <div class="mb-4 flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-300"><i class="fa fa-lock"></i></span>
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('general.pages.cash_register.close_register') }}</h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.closing_balance') }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.closing_balance') }}</label>
                                        <input type="number" step="any" class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500 dark:border-rose-500/30 dark:bg-slate-900 dark:text-white dark:focus:border-rose-500" wire:model="closing_balance_input">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.notes') }}</label>
                                        <textarea rows="2" class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500 dark:border-rose-500/30 dark:bg-slate-900 dark:text-white dark:focus:border-rose-500" wire:model="closing_notes"></textarea>
                                    </div>
                                    <button wire:click="closeRegister" class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">{{ __('general.pages.cash_register.close_register') }}</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-3xl bg-gradient-to-br from-emerald-500/15 via-white to-brand-500/10 p-5 dark:from-emerald-500/10 dark:via-slate-900 dark:to-brand-500/10">
                            <div class="flex items-start gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500 text-lg text-white shadow-lg shadow-emerald-500/25"><i class="fa fa-cash-register"></i></span>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.cash_register.open_close_register') }}</h3>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.open_register') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.opening_balance') }}</label>
                                <input type="number"  wire:model="opening_balance_input" step="any" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-all focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-500">
                            </div>

                            @if(admin()->branch_id === null)
                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('general.pages.cash_register.select_branch') }}</label>
                                    <select class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-all focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-500 select2" name="branchId">
                                        <option value="">{{ __('general.pages.cash_register.select_branch') }}</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" @selected($branchId == $branch->id)>{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <button wire:click="openRegister" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">{{ __('general.pages.cash_register.open_register') }}</button>
                        </div>
                    @endif
                </div>
            </x-tenant-tailwind-gemini.table-card>
        </div>
    </div>
@else
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-600 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300">
        {{ __('general.messages.you_do_not_have_permission_to_access') }}
    </div>
@endif

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
