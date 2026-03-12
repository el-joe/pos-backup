<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.payment-methods.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.payment-methods.search') }}
                </label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.payment-methods.search_placeholder') }}"
                       wire:model.blur="filters.name">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.payment-methods.branch') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.branch_id">
                    <option value="all">{{ __('general.layout.all_branches') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.payment-methods.status') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.active">
                    <option value="all" {{ ($filters['active'] ?? '') == 'all' ? 'selected' : '' }}>{{ __('general.pages.payment-methods.all') }}</option>
                    <option value="1" {{ ($filters['active'] ?? '') == '1' ? 'selected' : '' }}>{{ __('general.pages.payment-methods.active') }}</option>
                    <option value="0" {{ ($filters['active'] ?? '') == '0' ? 'selected' : '' }}>{{ __('general.pages.payment-methods.inactive') }}</option>
                </select>
            </div>

            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-3 lg:justify-end">
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.payment-methods.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.payment-methods')" icon="fa fa-credit-card">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('payment_methods.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900"
                            wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.payment-methods.export') }}
                    </button>
                @endadminCan

                @adminCan('payment_methods.create')
                    <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                            data-bs-toggle="modal"
                            data-bs-target="#editPaymentMethodModal"
                            wire:click="setCurrent(null)">
                        <i class="fa fa-plus"></i> {{ __('general.pages.payment-methods.new_payment_method') }}
                    </button>
                @endadminCan
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.payment-methods.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.payment-methods.name') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.payment-methods.branch_name') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.payment-methods.status') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.payment-methods.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($paymentMethods as $paymentMethod)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $paymentMethod->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $paymentMethod->name }}</td>
                        <td class="px-5 py-4">{{ $paymentMethod->branch?->name ?? __('general.layout.all_branches') }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $paymentMethod->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                {{ $paymentMethod->active ? __('general.pages.payment-methods.active') : __('general.pages.payment-methods.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!in_array($paymentMethod->slug, ['cash', 'bank-transfer', 'check']))
                                    @adminCan('payment_methods.update')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editPaymentMethodModal"
                                                wire:click="setCurrent({{ $paymentMethod->id }})"
                                                title="{{ __('general.pages.payment-methods.edit') }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endadminCan
                                    @adminCan('payment_methods.delete')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                                                wire:click="deleteAlert({{ $paymentMethod->id }})"
                                                title="{{ __('general.pages.payment-methods.delete') }}">
                                            <i class="fa fa-times text-lg"></i>
                                        </button>
                                    @endadminCan
                                @else
                                    <i class="fa fa-lock text-slate-400 dark:text-slate-500" title="{{ __('general.pages.payment-methods.lock_tooltip') }}"></i>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            No data found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($paymentMethods->hasPages())
            <x-slot:footer>
                {{ $paymentMethods->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    <div class="modal fade" id="editPaymentMethodModal" tabindex="-1" aria-labelledby="editPaymentMethodModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-3xl border-0 shadow-2xl dark:bg-slate-900">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 dark:border-slate-800 dark:bg-slate-950/70">
                    <h5 class="text-lg font-semibold text-slate-900 dark:text-white" id="editPaymentMethodModalLabel">
                        {{ $current?->id ? __('general.pages.payment-methods.edit_payment_method') : __('general.pages.payment-methods.new_payment_method') }}
                    </h5>
                </div>

                <div class="modal-body space-y-4 bg-white px-6 py-6 dark:bg-slate-900">
                    <div>
                        <label for="expenseCategoryName" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.payment-methods.name') }}</label>
                        <input type="text"
                               class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                               wire:model="data.name"
                               id="expenseCategoryName"
                               placeholder="{{ __('general.pages.payment-methods.enter_payment_method_name') }}">
                    </div>

                    <div>
                        <label for="branch_id" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.payment-methods.branch') }}</label>
                        @if(admin()->branch_id == null)
                            <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                                    id="branch_id"
                                    name="data.branch_id">
                                <option value="">{{ __('general.layout.all_branches') }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($data['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                                   value="{{ admin()->branch?->name }}"
                                   disabled>
                        @endif
                    </div>

                    <label class="inline-flex items-center gap-3 text-sm text-slate-700 dark:text-slate-300">
                        <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" id="expenseCategoryActive" wire:model="data.active">
                        <span>{{ __('general.pages.payment-methods.is_active') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                    <button type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                            data-bs-dismiss="modal">
                        {{ __('general.pages.payment-methods.close') }}
                    </button>
                    <button type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600"
                            wire:click="save">
                        {{ __('general.pages.payment-methods.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
