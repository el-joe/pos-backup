<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.details_title') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">#{{ $expense->id }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.amount') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($expense->amount ?? 0, true) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.tax_percentage') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $expense->tax_percentage ?? 0 }}%</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.date') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $expense->expense_date }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.depreciation_expenses.details_title') . ' #' . $expense->id" icon="fa fa-line-chart">
        <x-slot:actions>
            @if($expense->model)
                <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" href="{{ route('admin.fixed-assets.details', $expense->model->id) }}">
                    <i class="fa fa-building"></i> {{ __('general.pages.depreciation_expenses.view_asset') }}
                </a>
            @endif
        </x-slot:actions>

        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.branch') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $expense->branch?->name ?? '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $expense->model?->code ?? '' }} {{ $expense->model?->name ?? $expense->model_id }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.category') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $expense->category?->display_name ?? '—' }}</p>
            </div>

            @if($expense->note)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 md:col-span-2 xl:col-span-3 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.note') }}</p>
                    <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-300">{{ $expense->note }}</p>
                </div>
            @endif
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
