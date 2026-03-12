<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.transactions.filters')" icon="fa fa-filter" :expanded="$collapseFilters">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.transactions.transaction_id') }}</label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.transactions.search_placeholder') }}"
                       wire:model.blur="filters.transaction_id">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.transactions.transaction_type') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.transaction_type">
                    <option value="all">{{ __('general.pages.transactions.all') }}</option>
                    @foreach (App\Enums\TransactionTypeEnum::cases() as $case)
                        <option value="{{ $case->value }}" {{ ($filters['transaction_type'] ?? '') == $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.transactions.branch') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.branch_id">
                    <option value="all">{{ __('general.pages.transactions.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.transactions.date') }}</label>
                <input type="date"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                       wire:model.live="filters.date">
            </div>

            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-4 lg:justify-end">
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.transactions.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.transactions.transactions_list')" icon="fa fa-exchange" :render-table="false">
        <x-slot:actions>
            @adminCan('transactions.export')
                <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i> {{ __('general.pages.transactions.export') }}
                </button>
            @endadminCan
        </x-slot:actions>

        @include('admin.partials.tableHandler',[
            'rows'=> $transactionLines,
            'columns' => $columns,
            'headers' => $headers,
        ])
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
