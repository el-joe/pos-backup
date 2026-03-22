<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.discounts.filters')" icon="fa fa-filter" :expanded="$collapseFilters">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.discounts.search_label') }}</label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                    placeholder="{{ __('general.pages.discounts.search_placeholder') }}"
                    wire:model.blur="filters.search">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.discounts.start_date') }}</label>
                <input type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model.live="filters.start_date">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.discounts.end_date') }}</label>
                <input type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model.live="filters.end_date">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.discounts.status') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.discounts.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.discounts.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.discounts.inactive') }}</option>
                </select>
            </div>

            <div class="md:col-span-2 xl:col-span-4 flex justify-end">
                <button class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i>
                    {{ __('general.pages.discounts.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.discounts')" icon="fa-percent">
        <x-slot:actions>
            @adminCan('discounts.export')
                <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i>
                    {{ __('general.pages.discounts.export') }}
                </button>
            @endadminCan
            @adminCan('discounts.create')
                <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" data-bs-toggle="modal" data-bs-target="#editDiscountModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i>
                    {{ __('general.pages.discounts.new_discount') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400 rtl:text-right">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.discounts.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.discounts.name') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.discounts.code') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.discounts.value') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.discounts.start_date') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.discounts.end_date') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.discounts.status') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.discounts.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($discounts as $discount)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $discount->id }}</td>
                        <td class="px-5 py-4 text-slate-700 dark:text-slate-200">{{ $discount->name }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $discount->code }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $discount->value }} {{ $discount->type === 'rate' ? '%' : currency()->symbol }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ dateTimeFormat($discount->start_date,true,false) }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ dateTimeFormat($discount->end_date,true,false) }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $discount->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                {{ $discount->active ? __('general.pages.discounts.active') : __('general.pages.discounts.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('discounts.update')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" data-bs-toggle="modal" data-bs-target="#editDiscountModal" wire:click="setCurrent({{ $discount->id }})" title="{{ __('general.pages.discounts.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                @endadminCan
                                @adminCan('discounts.delete')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $discount->id }})" title="{{ __('general.pages.discounts.delete') }}">
                                        <i class="fa fa-times text-lg"></i>
                                    </button>
                                @endadminCan
                                <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-sky-600 transition-colors hover:bg-sky-50 dark:text-sky-400 dark:hover:bg-sky-500/10" data-bs-toggle="modal" data-bs-target="#historyModal" wire:click="setCurrent({{ $discount->id }})" title="{{ __('general.pages.discounts.history') }}">
                                    <i class="fa fa-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            No data found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($discounts->hasPages())
            <x-slot:footer>
                {{ $discounts->links('pagination::default5') }}
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
