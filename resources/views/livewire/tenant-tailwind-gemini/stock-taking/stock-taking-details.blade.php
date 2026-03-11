<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.stock_take_details') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">#{{ $stockTake->id }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.branch') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $stockTake->branch?->name ?? __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.date') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $stockTake->date ? dateTimeFormat($stockTake->date, true, false) : __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.created_at') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ formattedDateTime($stockTake->created_at) }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stock-taking.stock_take_details') . ' #' . $stockTake->id" icon="fa fa-clipboard-list">
        <x-slot:head>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('activeTab', 'details')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'details' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-info-circle"></i>
                    {{ __('general.pages.stock-taking.details') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'product_stocks')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'product_stocks' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-cubes"></i>
                    {{ __('general.pages.stock-taking.product_stocks') }}
                </button>
            </div>
        </x-slot:head>

        @if($activeTab === 'details')
            <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.branch') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $stockTake->branch?->name ?? __('general.messages.n_a') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.date') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $stockTake->date ? dateTimeFormat($stockTake->date, true, false) : __('general.messages.n_a') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.created_at') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ formattedDateTime($stockTake->created_at) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.note') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $stockTake->note ?? __('general.messages.n_a') }}</p>
                </div>
            </div>
        @else
            <div class="p-5">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('general.pages.stock-taking.product') }}</th>
                                <th>{{ __('general.pages.stock-taking.unit') }}</th>
                                <th>{{ __('general.pages.stock-taking.current_stock') }}</th>
                                <th>{{ __('general.pages.stock-taking.actual_stock') }}</th>
                                <th>{{ __('general.pages.stock-taking.difference') }}</th>
                                <th>{{ __('general.pages.stock-taking.status') }}</th>
                                <th>{{ __('general.pages.stock-taking.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockTake->products as $stProduct)
                                @php
                                    $stock = $stProduct->stock;
                                    $difference = $stProduct->difference;
                                    if($difference > 0) {
                                        $badgeClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300';
                                        $status = __('general.pages.stock-taking.surplus');
                                        $sign = '+';
                                    } elseif($difference < 0) {
                                        $badgeClass = 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300';
                                        $status = __('general.pages.stock-taking.shortage');
                                        $sign = '';
                                    } else {
                                        $badgeClass = 'bg-slate-100 text-slate-700 dark:bg-slate-700/60 dark:text-slate-200';
                                        $status = __('general.pages.stock-taking.no_change');
                                        $sign = '';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $stock->product?->name }}</td>
                                    <td>{{ $stock->unit?->name }}</td>
                                    <td>{{ $stProduct->current_qty }}</td>
                                    <td>{{ $stProduct->actual_qty }}</td>
                                    <td>{{ $sign }}{{ $difference }}</td>
                                    <td><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $status }}</span></td>
                                    <td>
                                        @if($stProduct->returned == 0)
                                            <button class="inline-flex items-center gap-2 rounded-2xl bg-amber-500 px-3 py-2 text-sm font-semibold text-white transition hover:bg-amber-600" wire:click="returnStockAlert({{ $stProduct->id }})">
                                                <i class="fa fa-undo"></i> {{ __('general.pages.stock-taking.return') }}
                                            </button>
                                        @else
                                            <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-400 dark:border-slate-700 dark:text-slate-500" disabled>
                                                <i class="fa fa-check"></i> {{ __('general.pages.stock-taking.returned') }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>
