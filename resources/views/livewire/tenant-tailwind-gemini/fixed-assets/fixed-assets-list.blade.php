<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.fixed_assets.filters')" icon="fa fa-filter" :expanded="$collapseFilters">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.fixed_assets.search') }}</label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.fixed_assets.search_placeholder') }}"
                       wire:model.blur="filters.search">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.fixed_assets.branch') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.branch_id">
                    <option value="">{{ __('general.layout.all_branches') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.fixed_assets.status') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.status">
                    <option value="">{{ __('general.pages.fixed_assets.all_statuses') }}</option>
                    <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_active') }}</option>
                    <option value="under_construction" {{ ($filters['status'] ?? '') == 'under_construction' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_under_construction') }}</option>
                    <option value="disposed" {{ ($filters['status'] ?? '') == 'disposed' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_disposed') }}</option>
                    <option value="sold" {{ ($filters['status'] ?? '') == 'sold' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_sold') }}</option>
                </select>
            </div>
            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-3 lg:justify-end">
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.fixed_assets.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.fixed_assets.fixed_assets')" icon="fa fa-building">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i> {{ __('general.pages.fixed_assets.export') }}
                </button>
                <a class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                   href="{{ route('admin.fixed-assets.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.fixed_assets.new_fixed_asset') }}
                </a>
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">#</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.code') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.name') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.branch') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.status') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.payment_status') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.payments') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.due_amount') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.cost') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.fixed_assets.net_book_value') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.fixed_assets.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($assets as $asset)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $asset->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $asset->code }}</td>
                        <td class="px-5 py-4">{{ $asset->name }}</td>
                        <td class="px-5 py-4">{{ $asset->branch?->name ?? '—' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $asset->status === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : ($asset->status === 'under_construction' ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : ($asset->status === 'sold' ? 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-400' : 'bg-slate-100 text-slate-700 dark:bg-slate-700/50 dark:text-slate-300')) }}">
                                @if($asset->status === 'active')
                                    {{ __('general.pages.fixed_assets.status_active') }}
                                @elseif($asset->status === 'under_construction')
                                    {{ __('general.pages.fixed_assets.status_under_construction') }}
                                @elseif($asset->status === 'sold')
                                    {{ __('general.pages.fixed_assets.status_sold') }}
                                @else
                                    {{ __('general.pages.fixed_assets.status_disposed') }}
                                @endif
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $paymentState = $asset->payment_state;
                                $paymentClass = match($paymentState) {
                                    'paid' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    'partial_paid' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                    'check' => 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-400',
                                    default => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                                };
                                $paymentLabel = match($paymentState) {
                                    'paid' => __('general.pages.fixed_assets.payment_paid'),
                                    'partial_paid' => __('general.pages.fixed_assets.payment_partial'),
                                    'check' => __('general.pages.fixed_assets.payment_check'),
                                    default => __('general.pages.fixed_assets.payment_unpaid'),
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $paymentClass }}">{{ $paymentLabel }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @php($latestPayment = $asset->orderPayments->sortByDesc('id')->first())
                            <div class="font-medium text-slate-900 dark:text-white">{{ $asset->orderPayments->count() }} {{ __('general.pages.fixed_assets.payments_count') }}</div>
                            @if($latestPayment)
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $latestPayment->account ? (($latestPayment->account->paymentMethod?->name ? $latestPayment->account->paymentMethod?->name.' - ' : '').$latestPayment->account->name) : __('general.messages.n_a') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ ($asset->due_amount ?? 0) > 0 ? 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' }}">
                                {{ currencyFormat($asset->due_amount ?? 0, true) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">{{ currencyFormat($asset->cost ?? 0, true) }}</td>
                        <td class="px-5 py-4">{{ currencyFormat($asset->net_book_value ?? 0, true) }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a class="inline-flex h-8 items-center rounded-lg border border-slate-200 px-3 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                                   href="{{ route('admin.fixed-assets.details', $asset->id) }}">
                                    {{ __('general.pages.fixed_assets.details') }}
                                </a>
                                @adminCan('fixed_assets.update')
                                    @if(($asset->due_amount ?? 0) > 0)
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-600 transition-colors hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-500/10"
                                                wire:click="setCurrent({{ $asset->id }})"
                                                data-bs-toggle="modal"
                                                data-bs-target="#paymentModal"
                                                title="{{ __('general.pages.fixed_assets.save_payment') }}">
                                            <i class="fa fa-credit-card"></i>
                                        </button>
                                    @endif
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.no_records') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            {{ $assets->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>

    <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-3xl border-0 shadow-2xl dark:bg-slate-900">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 dark:border-slate-800 dark:bg-slate-950/70">
                    <div class="flex items-center justify-between gap-3">
                        <h5 class="text-lg font-semibold text-slate-900 dark:text-white" id="paymentModalLabel">{{ __('general.pages.fixed_assets.payment_modal_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body space-y-5 bg-white px-6 py-6 dark:bg-slate-900">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="paymentAmount" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.fixed_assets.amount') }}</label>
                            <div class="flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900">
                                <span class="mr-2 text-sm text-slate-500 dark:text-slate-400">{{ currency()->symbol }}</span>
                                <input type="number"
                                       class="w-full border-0 bg-transparent p-0 text-sm text-slate-900 focus:outline-none focus:ring-0 dark:text-white"
                                       id="paymentAmount"
                                       wire:model="payment.amount"
                                       placeholder="{{ __('general.pages.fixed_assets.amount') }}">
                                <span class="ml-3 text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('general.pages.fixed_assets.due_amount') }}:
                                    <strong class="text-rose-600 dark:text-rose-400">{{ number_format($current->due_amount ?? 0, 2) }}</strong>
                                </span>
                            </div>
                            @error('payment.amount')
                                <small class="text-rose-600 dark:text-rose-400">{{ $message }}</small>
                            @enderror
                        </div>

                        <div>
                            <label for="paymentMethod" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.fixed_assets.payment_account') }}</label>
                            <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                                    id="paymentMethod"
                                    wire:model="payment.account_id">
                                <option value="">{{ __('general.pages.fixed_assets.select_payment_account') }}</option>
                                @foreach (collect($paymentAccounts ?? []) as $paymentAcc)
                                    <option value="{{ data_get($paymentAcc, 'id') }}">
                                        {{ data_get($paymentAcc, 'paymentMethod.name') }} - {{ data_get($paymentAcc, 'name') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment.account_id')
                                <small class="text-rose-600 dark:text-rose-400">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="paymentNote" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.fixed_assets.note') }}</label>
                        <textarea class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                                  id="paymentNote"
                                  wire:model="payment.note"
                                  rows="3"
                                  placeholder="{{ __('general.pages.fixed_assets.note_optional') }}"></textarea>
                        @error('payment.note')
                            <small class="text-rose-600 dark:text-rose-400">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="button"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-700"
                            wire:click="savePayment">
                        <i class="fa fa-check"></i> {{ __('general.pages.fixed_assets.save_payment') }}
                    </button>

                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                        <h5 class="mb-3 text-sm font-semibold uppercase tracking-[0.16em] text-slate-600 dark:text-slate-300">{{ __('general.pages.fixed_assets.recent_payments') }}</h5>
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
                                <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.fixed_assets.date') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.fixed_assets.amount') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.fixed_assets.method') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.fixed_assets.note') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                                    @forelse(($current?->orderPayments ?? collect())->take(10) as $p)
                                        <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                            <td class="px-4 py-3">{{ dateTimeFormat($p->created_at,true,false) }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">{{ currencyFormat($p->amount, true) }}</span>
                                            </td>
                                            <td class="px-4 py-3">{{ $p->account ? (($p->account->paymentMethod?->name ? $p->account->paymentMethod?->name.' - ' : '').$p->account->name) : __('general.messages.n_a') }}</td>
                                            <td class="px-4 py-3">{{ $p->note }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.no_payments') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                    <button type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                            data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('general.pages.fixed_assets.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
