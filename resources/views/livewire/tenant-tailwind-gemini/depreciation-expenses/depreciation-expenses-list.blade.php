<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.depreciation_expenses.filters')" icon="fa fa-filter" :expanded="$collapseFilters">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.depreciation_expenses.branch') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.branch_id">
                    <option value="">{{ __('general.layout.all_branches') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.model_id">
                    <option value="">{{ __('general.pages.depreciation_expenses.all_assets') }}</option>
                    @foreach ($assets as $asset)
                        <option value="{{ $asset->id }}" {{ ($filters['model_id'] ?? '') == $asset->id ? 'selected' : '' }}>{{ $asset->code }} - {{ $asset->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.depreciation_expenses.category') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.expense_category_id">
                    <option value="">{{ __('general.pages.depreciation_expenses.all_categories') }}</option>
                    @foreach ($expenseCategories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['expense_category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.depreciation_expenses.date') }}</label>
                <input type="date"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                       wire:model="filters.date" />
            </div>
            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-4 lg:justify-end">
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.depreciation_expenses.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.depreciation_expenses.depreciation_expenses')" icon="fa fa-line-chart">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i> {{ __('general.pages.depreciation_expenses.export') }}
                </button>
                <a class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                   href="{{ route('admin.depreciation-expenses.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.depreciation_expenses.new_asset_entry') }}
                </a>
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">#</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.depreciation_expenses.branch') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.depreciation_expenses.category') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.depreciation_expenses.amount') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.depreciation_expenses.date') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.depreciation_expenses.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($expenses as $expense)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $expense->id }}</td>
                        <td class="px-5 py-4">{{ $expense->branch?->name ?? '—' }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $expense->model?->code ?? '' }} {{ $expense->model?->name ?? $expense->model_id }}</td>
                        <td class="px-5 py-4">{{ $expense->category?->display_name ?? '—' }}</td>
                        <td class="px-5 py-4">{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                        <td class="px-5 py-4">{{ $expense->expense_date }}</td>
                        <td class="px-5 py-4 text-right">
                            <a class="inline-flex h-8 items-center rounded-lg border border-slate-200 px-3 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                               href="{{ route('admin.depreciation-expenses.details', $expense->id) }}">
                                {{ __('general.pages.depreciation_expenses.details') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.no_records') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            {{ $expenses->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
