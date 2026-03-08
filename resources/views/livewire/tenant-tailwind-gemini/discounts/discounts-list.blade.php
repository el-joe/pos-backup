@php
    $activeDiscounts = collect($discounts->items() ?? [])->where('active', true)->count();
    $inactiveDiscounts = collect($discounts->items() ?? [])->where('active', false)->count();
@endphp

<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-3">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-brand-500/15 to-brand-500/5 p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.titles.discounts') }}</div>
                <div class="mt-3 text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $discounts->total() }}</div>
            </div>
        </div>
        <div class="overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-sm dark:border-emerald-500/20 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-emerald-500/15 to-emerald-500/5 p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.active') }}</div>
                <div class="mt-3 text-3xl font-black tracking-tight text-emerald-700 dark:text-emerald-300">{{ $activeDiscounts }}</div>
            </div>
        </div>
        <div class="overflow-hidden rounded-3xl border border-rose-200 bg-white shadow-sm dark:border-rose-500/20 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-rose-500/15 to-rose-500/5 p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.inactive') }}</div>
                <div class="mt-3 text-3xl font-black tracking-tight text-rose-700 dark:text-rose-300">{{ $inactiveDiscounts }}</div>
            </div>
        </div>
    </div>

    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.discounts.filters')" icon="fa-filter" :expanded="!$collapseFilters">
        <x-slot:actions>
            <button class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                    wire:click="$toggle('collapseFilters')">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.discounts.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.search_label') }}</label>
                <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900"
                    placeholder="{{ __('general.pages.discounts.search_placeholder') }}"
                    wire:model.blur="filters.search">
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.start_date') }}</label>
                <input type="date" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model.live="filters.start_date">
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.end_date') }}</label>
                <input type="date" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model.live="filters.end_date">
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.status') }}</label>
                <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.discounts.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.discounts.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.discounts.inactive') }}</option>
                </select>
            </div>

            <div class="md:col-span-2 xl:col-span-4 flex justify-end">
                <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900" wire:click="resetFilters">
                    <i class="fa fa-undo"></i>
                    {{ __('general.pages.discounts.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.discounts')" description="Configure promotional discounts, lifecycle dates, and usage history from a single dashboard." icon="fa-percent">
        <x-slot:actions>
            @adminCan('discounts.export')
                <button class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i>
                    {{ __('general.pages.discounts.export') }}
                </button>
            @endadminCan
            @adminCan('discounts.create')
                <button class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" data-bs-toggle="modal" data-bs-target="#editDiscountModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i>
                    {{ __('general.pages.discounts.new_discount') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800 rtl:text-right">
            <thead class="bg-slate-50/80 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.id') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.name') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.code') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.value') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.start_date') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.end_date') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.status') }}</th>
                    <th class="px-5 py-4 text-center font-semibold">{{ __('general.pages.discounts.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($discounts as $discount)
                    <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">#{{ $discount->id }}</td>
                        <td class="px-5 py-4 text-slate-700 dark:text-slate-200">{{ $discount->name }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $discount->code }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $discount->value }} {{ $discount->type === 'rate' ? '%' : currency()->symbol }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ dateTimeFormat($discount->start_date,true,false) }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ dateTimeFormat($discount->end_date,true,false) }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $discount->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">
                                {{ $discount->active ? __('general.pages.discounts.active') : __('general.pages.discounts.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-center gap-2">
                                @adminCan('discounts.update')
                                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-400/50 dark:hover:text-brand-300" data-bs-toggle="modal" data-bs-target="#editDiscountModal" wire:click="setCurrent({{ $discount->id }})" title="{{ __('general.pages.discounts.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                @endadminCan
                                @adminCan('discounts.delete')
                                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-rose-200 text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $discount->id }})" title="{{ __('general.pages.discounts.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endadminCan
                                <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-sky-200 text-sky-600 transition hover:bg-sky-50 dark:border-sky-500/30 dark:text-sky-300 dark:hover:bg-sky-500/10" data-bs-toggle="modal" data-bs-target="#historyModal" wire:click="setCurrent({{ $discount->id }})" title="{{ __('general.pages.discounts.history') }}">
                                    <i class="fa fa-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center">
                            <div class="mx-auto max-w-sm space-y-2">
                                <div class="text-base font-semibold text-slate-900 dark:text-white">No discounts found.</div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Create a discount to start managing promotions and time-based offers.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($discounts->hasPages())
            <x-slot:footer>
                <div class="flex justify-center">
                    {{ $discounts->links('pagination::default5') }}
                </div>
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    <div class="modal fade" id="editDiscountModal" tabindex="-1" aria-labelledby="editDiscountModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-[28px] border-0 shadow-2xl dark:bg-slate-900">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 dark:border-slate-800 dark:bg-slate-950/70">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h5 class="text-lg font-semibold text-slate-900 dark:text-white" id="editDiscountModalLabel">{{ $current?->id ? __('general.pages.discounts.edit_discount') : __('general.pages.discounts.new_discount') }}</h5>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Set scope, value, and activation rules for the discount.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body bg-white px-6 py-6 dark:bg-slate-900">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="discountName" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.name') }}</label>
                            <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.name" id="discountName" placeholder="{{ __('general.pages.discounts.enter_discount_name') }}">
                        </div>
                        <div>
                            <label for="discountCode" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.code') }}</label>
                            <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.code" id="discountCode" placeholder="{{ __('general.pages.discounts.enter_discount_code') }}">
                        </div>
                        <div>
                            <label for="discountBranch" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.branch') }}</label>
                            <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.branch_id" id="discountBranch">
                                <option value="">{{ __('general.pages.discounts.all_branches_option') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="discountType" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.type') }}</label>
                            <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.type" id="discountType">
                                <option value="">{{ __('general.pages.discounts.select_type') }}</option>
                                <option value="rate" {{ ($data['type']??'') == 'rate' ? 'selected' : '' }}>{{ __('general.pages.discounts.rate') }}</option>
                                <option value="fixed" {{ ($data['type']??'') == 'fixed' ? 'selected' : '' }}>{{ __('general.pages.discounts.fixed') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="discountValue" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.value') }}</label>
                            <div class="mt-2 flex overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-950">
                                <span class="inline-flex items-center border-e border-slate-200 px-4 text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                    <i class="fa fa-{{ ($data['type']??false) == 'fixed' ? 'dollar' : 'percent' }}"></i>
                                </span>
                                <input type="number" step="any" wire:model="data.value" id="discountValue" class="w-full bg-transparent px-4 py-3 text-sm text-slate-900 outline-none dark:text-white" placeholder="{{ __('general.pages.discounts.enter_value') }}">
                            </div>
                        </div>
                        <div>
                            <label for="discountStartDate" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.start_date') }}</label>
                            <input type="date" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.start_date" id="discountStartDate">
                        </div>
                        <div>
                            <label for="discountEndDate" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.end_date') }}</label>
                            <input type="date" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.end_date" id="discountEndDate">
                        </div>

                        @isset($data['type'])
                            @if($data['type'] == 'rate')
                                <div>
                                    <label for="discountMaxAmount" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.max_discount_amount') }}</label>
                                    <input type="number" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.max_discount_amount" id="discountMaxAmount" placeholder="{{ __('general.pages.discounts.enter_max_discount_amount') }}">
                                </div>
                            @else
                                <div>
                                    <label for="discountSalesThreshold" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.sales_threshold') }}</label>
                                    <input type="number" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.sales_threshold" id="discountSalesThreshold" placeholder="{{ __('general.pages.discounts.enter_sales_threshold_amount') }}">
                                    <p class="mt-2 text-xs text-amber-600 dark:text-amber-300">{{ __('general.pages.discounts.sales_threshold_note') }}</p>
                                </div>
                            @endif
                        @endisset

                        <div>
                            <label for="discountUsageLimit" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.discounts.usage_limit') }}</label>
                            <input type="number" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.usage_limit" id="discountUsageLimit" placeholder="{{ __('general.pages.discounts.enter_usage_limit') }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                <input class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" type="checkbox" id="discountActive" wire:model="data.active">
                                <span>{{ __('general.pages.discounts.is_active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                    <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900" data-bs-dismiss="modal">{{ __('general.pages.discounts.close') }}</button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" wire:click="save">{{ __('general.pages.discounts.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-[28px] border-0 shadow-2xl dark:bg-slate-900">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 dark:border-slate-800 dark:bg-slate-950/70">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h5 class="text-lg font-semibold text-slate-900 dark:text-white" id="historyModalLabel">{{ __('general.pages.discounts.discount_history') }} - {{ $current?->name }}</h5>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Review where and when this discount was applied.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body bg-white px-6 py-6 dark:bg-slate-900">
                    <div class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800 rtl:text-right">
                            <thead class="bg-slate-50/80 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/50 dark:text-slate-400">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.id') }}</th>
                                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.target_type') }}</th>
                                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.target_id') }}</th>
                                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.discounts.date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse ($current?->history ?? [] as $history)
                                    <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">#{{ $history->id }}</td>
                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ App\Models\Tenant\DiscountHistory::$relatedWith[$history->target_type] ?? $history->target_type }}</td>
                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $history->target_id }}</td>
                                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ dateTimeFormat($history->created_at, true, false) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-400">No history records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
