<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.expense-categories.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expense-categories.search') }}</label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" placeholder="{{ __('general.pages.expense-categories.search_placeholder') }}" wire:model.blur="filters.name">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expense-categories.status') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.inactive') }}</option>
                </select>
            </div>

            <div class="flex items-end justify-start md:col-span-2 lg:col-span-1 lg:justify-end">
                <button class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.expense-categories.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.expense-categories')" icon="fa-tags">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('expense_categories.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.expense-categories.export') }}
                    </button>
                @endadminCan
                @adminCan('expense_categories.create')
                    <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" data-bs-toggle="modal" data-bs-target="#editExpenseCategoryModal" wire:click="setCurrent(null)">
                        <i class="fa fa-plus"></i> {{ __('general.pages.expense-categories.new_expense_category') }}
                    </button>
                @endadminCan
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.expense-categories.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.expense-categories.ar_name') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.expense-categories.name') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.expense-categories.status_label') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.expense-categories.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($expenseCategories as $expenseCategory)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $expenseCategory->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $expenseCategory->ar_name }}</td>
                        <td class="px-5 py-4">{{ $expenseCategory->name }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $expenseCategory->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                {{ $expenseCategory->active ? __('general.pages.expense-categories.active') : __('general.pages.expense-categories.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($expenseCategory->default != 1)
                                    @adminCan('expense_categories.update')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" data-bs-toggle="modal" data-bs-target="#editExpenseCategoryModal" wire:click="setCurrent({{ $expenseCategory->id }})" title="{{ __('general.pages.expense-categories.edit') }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endadminCan
                                    @adminCan('expense_categories.delete')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $expenseCategory->id }})" title="{{ __('general.pages.expense-categories.delete') }}">
                                            <i class="fa fa-times text-lg"></i>
                                        </button>
                                    @endadminCan
                                @else
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 dark:text-slate-500" title="Locked"><i class="fa fa-ban"></i></span>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @foreach ($expenseCategory->children as $child)
                        <tr class="bg-slate-50/60 transition-colors hover:bg-slate-100/70 dark:bg-slate-900/40 dark:hover:bg-slate-800/60">
                            <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $child->id }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="h-px w-6 bg-slate-300 dark:bg-slate-700"></span>
                                    <span class="font-medium text-slate-900 dark:text-white">{{ $child->ar_name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-700 dark:text-slate-300">{{ $child->name }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $child->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                    {{ $child->active ? __('general.pages.expense-categories.active') : __('general.pages.expense-categories.inactive') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($child->default != 1)
                                        @adminCan('expense_categories.update')
                                            <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" data-bs-toggle="modal" data-bs-target="#editExpenseCategoryModal" wire:click="setCurrent({{ $child->id }})" title="{{ __('general.pages.expense-categories.edit') }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        @endadminCan
                                        @adminCan('expense_categories.delete')
                                            <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $child->id }})" title="{{ __('general.pages.expense-categories.delete') }}">
                                                <i class="fa fa-times text-lg"></i>
                                            </button>
                                        @endadminCan
                                    @else
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 dark:text-slate-500" title="Locked"><i class="fa fa-ban"></i></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-tenant-tailwind-gemini.table-card>

    <div class="modal fade" id="editExpenseCategoryModal" tabindex="-1" aria-labelledby="editExpenseCategoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-[24px] border-0 shadow-2xl dark:bg-slate-900">
                <div class="bg-gradient-to-r from-brand-600 to-sky-500 px-6 py-5 text-white">
                    <div class="flex items-center justify-between gap-4">
                        <h5 class="text-lg font-semibold">{{ $current?->id ? __('general.pages.expense-categories.edit_expense_category') : __('general.pages.expense-categories.new_expense_category') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="space-y-4 p-6">
                    <div>
                        <label for="expenseCategoryName" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expense-categories.name') }}</label>
                        <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="data.name" id="expenseCategoryName" placeholder="{{ __('general.pages.expense-categories.enter_expense_category_name') }}">
                    </div>

                    <div>
                        <label for="expenseCategoryArName" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expense-categories.ar_name') }}</label>
                        <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="data.ar_name" id="expenseCategoryArName" placeholder="{{ __('general.pages.expense-categories.enter_expense_category_ar_name') }}">
                    </div>

                    <div>
                        <label for="expenseCategoryParent" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expense-categories.parent_category') }}</label>
                        <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500" id="expenseCategoryParent" wire:model="data.parent_id">
                            <option value="">{{ __('general.pages.expense-categories.select_parent_category') }}</option>
                            @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}">{{ $parentCategory->{app()->getLocale() === 'ar' ? 'ar_name' : 'name'} ?? $parentCategory->name ?? '---' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                        <input class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-900" type="checkbox" id="expenseCategoryActive" wire:model="data.active">
                        {{ __('general.pages.expense-categories.is_active') }}
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-6 py-4 dark:border-slate-800">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-dismiss="modal">{{ __('general.pages.expense-categories.close') }}</button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-600" wire:click="save">{{ __('general.pages.expense-categories.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
