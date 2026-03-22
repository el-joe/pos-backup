<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.expenses.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.branch') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.branch_id">
                    <option value="">{{ __('general.layout.all_branches') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.category') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.expense_category_id">
                    <option value="">{{ __('general.pages.expenses.all_categories') }}</option>
                    @foreach($expenseCategories as $category)
                        <option value="{{ $category->id }}" {{ ($filters['expense_category_id'] ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.type') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model="filters.type">
                    <option value="">{{ __('general.pages.expenses.types.all_types') }}</option>
                    <option value="normal">{{ __('general.pages.expenses.types.normal') }}</option>
                    <option value="prepaid">{{ __('general.pages.expenses.types.prepaid') }}</option>
                    <option value="accrued">{{ __('general.pages.expenses.types.accrued') }}</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.date') }}</label>
                <input type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model="filters.date" />
            </div>

            <div class="md:col-span-2 xl:col-span-4 flex items-end justify-end">
                <button class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.expenses.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.expenses')" icon="fa-money" :render-table="false">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('expenses.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.expenses.export') }}
                    </button>
                @endadminCan
                @adminCan('expenses.create')
                    <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" data-bs-toggle="modal" data-bs-target="#addExpenseModal" wire:click="setCurrent(null)">
                        <i class="fa fa-plus"></i> {{ __('general.pages.expenses.new_expense') }}
                    </button>
                @endadminCan
            </div>
        </x-slot:actions>

        @include('admin.partials.tableHandler',[
            'rows'=>$expenses,
            'columns'=>$columns,
            'headers'=>$headers
        ])
    </x-tenant-tailwind-gemini.table-card>

    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-[28px] border-0 shadow-2xl dark:bg-slate-900">
                <div class="bg-gradient-to-r from-brand-600 to-sky-500 px-6 py-5 text-white">
                    <div class="flex items-center justify-between gap-4">
                        <h5 class="text-lg font-semibold" id="addExpenseModalLabel">{{ $current?->id ? __('general.pages.expenses.edit_expense') : __('general.pages.expenses.new_expense') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="space-y-5 p-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="expenseBranch" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.branch') }}</label>
                            @if(admin()->branch_id == null)
                                <select id="expenseBranch" name="data.branch_id" class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500">
                                    <option value="">{{ __('general.pages.expenses.select_branch') }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200" value="{{ admin()->branch?->name }}" disabled>
                            @endif
                        </div>

                        <div>
                            <label for="expenseCategory" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.category') }}</label>
                            <select id="expenseCategory" name="data.expense_category_id" class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500">
                                <option value="">{{ __('general.pages.expenses.select_category') }}</option>
                                @foreach($expenseCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ ($data['expense_category_id']??'') == $cat->id ? 'selected' : '' }}>{{ $cat->display_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="expenseType" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.type') }}</label>
                            <select id="expenseType" name="data.type" class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500">
                                <option value="">{{ __('general.pages.expenses.types.all_types') }}</option>
                                @foreach(\App\Enums\Tenant\ExpenseTypeEnum::cases() as $type)
                                    <option value="{{ $type->value }}" {{ ($data['type']??'') == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="expenseAmount" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.amount') }}</label>
                            <input type="number" step="any" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500" wire:model="data.amount" id="expenseAmount" placeholder="{{ __('general.pages.expenses.enter_amount') }}">
                        </div>

                        <div>
                            <label for="taxPercentage" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.tax_percentage') }}</label>
                            <input type="number" step="any" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500" wire:model="data.tax_percentage" id="taxPercentage" placeholder="{{ __('general.pages.expenses.enter_tax_percentage') }}">
                        </div>

                        <div>
                            <label for="expenseDate" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.date') }}</label>
                            <input type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500" wire:model="data.expense_date" id="expenseDate">
                        </div>
                    </div>

                    <div>
                        <label for="expenseNote" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.expenses.note') }}</label>
                        <textarea class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" wire:model="data.note" id="expenseNote" rows="3" placeholder="{{ __('general.pages.expenses.enter_note') }}"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-6 py-4 dark:border-slate-800">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-dismiss="modal">{{ __('general.pages.expenses.close') }}</button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-600" wire:click="save">{{ __('general.pages.expenses.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
