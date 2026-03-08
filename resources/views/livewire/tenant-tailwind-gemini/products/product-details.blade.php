<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.24em] text-brand-600 dark:text-brand-300">{{ __('general.pages.products.details') }}</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $product->name }}</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.products.sku') }}: <strong>{{ $product->sku }}</strong>@if($product->code) <span class="mx-2">•</span>{{ __('general.pages.products.code') }}: <strong>{{ $product->code }}</strong>@endif</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.products.list') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"><i class="fa fa-list"></i> {{ __('general.pages.products.products') }}</a>
                @adminCan('products.update')
                <a href="{{ route('admin.products.add-edit', $product->id) }}" class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700"><i class="fa fa-edit"></i> {{ __('general.pages.products.edit') }}</a>
                @endadminCan
            </div>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="$product->name" icon="fa fa-cube">
        <x-slot:head>
            <div class="flex flex-wrap gap-2">
                @foreach (['overview' => 'fa fa-info-circle', 'stock' => 'fa fa-warehouse', 'sales' => 'fa fa-chart-line', 'purchases' => 'fa fa-shopping-bag', 'transfers' => 'fa fa-exchange-alt', 'adjustments' => 'fa fa-sliders-h'] as $tabKey => $tabIcon)
                    <button type="button" wire:click="setTab('{{ $tabKey }}')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $tab === $tabKey ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <i class="{{ $tabIcon }}"></i> {{ __('general.pages.products.tabs.' . $tabKey) }}
                    </button>
                @endforeach
            </div>
        </x-slot:head>

        @if($tab === 'overview')
            <div class="grid gap-4 p-5 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    @if($product->image_path)
                        <img src="{{ $product->image_path }}" class="mb-4 h-52 w-full rounded-2xl object-cover" alt="{{ $product->name }}">
                    @endif
                    <div class="space-y-2 text-sm text-slate-700 dark:text-slate-300">
                        <div><strong>{{ __('general.pages.products.name') }}:</strong> {{ $product->name }}</div>
                        <div><strong>{{ __('general.pages.products.sku') }}:</strong> {{ $product->sku }}</div>
                        <div><strong>{{ __('general.pages.products.unit') }}:</strong> {{ $product->unit?->name }}</div>
                        <div><strong>{{ __('general.pages.products.category') }}:</strong> {{ $product->category?->name }}</div>
                        <div><strong>{{ __('general.pages.products.brand') }}:</strong> {{ $product->brand?->name }}</div>
                        <div><strong>{{ __('general.pages.products.alert_quantity') }}:</strong> {{ $product->alert_qty ?? 0 }}</div>
                        <div><strong>{{ __('general.pages.products.status') }}:</strong> <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $product->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">{{ $product->active ? __('general.pages.products.active') : __('general.pages.products.inactive') }}</span></div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-sky-200 bg-sky-50 p-4 dark:border-sky-500/20 dark:bg-sky-500/10"><p class="text-xs font-medium uppercase tracking-[0.2em] text-sky-700 dark:text-sky-300">{{ __('general.pages.products.branch_stock') }}</p><p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $product->branch_stock }}</p></div>
                        <div class="rounded-2xl border border-brand-200 bg-brand-50 p-4 dark:border-brand-500/20 dark:bg-brand-500/10"><p class="text-xs font-medium uppercase tracking-[0.2em] text-brand-700 dark:text-brand-300">{{ __('general.pages.products.all_stock') }}</p><p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $product->all_stock }}</p></div>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('admin.stocks.list') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"><i class="fa fa-warehouse"></i> {{ __('general.pages.products.open_stocks_list') }}</a>
                    </div>
                </div>
            </div>
        @elseif($tab === 'stock')
            <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.products.branch') }}</th><th>{{ __('general.pages.products.unit') }}</th><th>{{ __('general.pages.products.qty') }}</th><th>{{ __('general.pages.products.unit_cost') }}</th><th>{{ __('general.pages.products.sell_price') }}</th></tr></thead><tbody>@forelse($stocks as $stock)<tr><td>{{ $stock->branch?->name }}</td><td>{{ $stock->unit?->name }}</td><td>{{ round($stock->qty, 3) }}</td><td>{{ number_format($stock->unit_cost ?? 0, 2) }}</td><td>{{ number_format($stock->sell_price ?? 0, 2) }}</td></tr>@empty<tr><td colspan="5" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.products.no_stock_records') }}</td></tr>@endforelse</tbody></table></div></div>
        @elseif($tab === 'sales')
            <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.sales.invoice_number') }}</th><th>{{ __('general.pages.sales.customer') }}</th><th>{{ __('general.pages.sales.branch') }}</th><th>{{ __('general.pages.products.qty') }}</th><th>{{ __('general.pages.sales.date') }}</th><th>{{ __('general.pages.sales.details') }}</th></tr></thead><tbody>@forelse($recentSalesItems as $item)<tr><td>{{ $item->sale?->invoice_number }}</td><td>{{ $item->sale?->customer?->name }}</td><td>{{ $item->sale?->branch?->name }}</td><td>{{ $item->actual_qty }}</td><td>{{ dateTimeFormat($item->sale?->order_date) }}</td><td class="text-nowrap">@if($item->sale)<a href="{{ route('admin.sales.details', $item->sale->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20"><i class="fa fa-eye"></i></a>@endif</td></tr>@empty<tr><td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.products.no_sales_records') }}</td></tr>@endforelse</tbody></table></div></div>
        @elseif($tab === 'purchases')
            <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.purchases.ref_no') }}</th><th>{{ __('general.pages.purchases.supplier') }}</th><th>{{ __('general.pages.purchases.branch') }}</th><th>{{ __('general.pages.products.qty') }}</th><th>{{ __('general.pages.purchases.order_date') }}</th><th>{{ __('general.pages.purchases.details') }}</th></tr></thead><tbody>@forelse($recentPurchaseItems as $item)<tr><td>{{ $item->purchase?->ref_no }}</td><td>{{ $item->purchase?->supplier?->name }}</td><td>{{ $item->purchase?->branch?->name }}</td><td>{{ $item->actual_qty }}</td><td>{{ dateTimeFormat($item->purchase?->order_date) }}</td><td class="text-nowrap">@if($item->purchase)<a href="{{ route('admin.purchases.details', $item->purchase->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20"><i class="fa fa-eye"></i></a>@endif</td></tr>@empty<tr><td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.products.no_purchase_records') }}</td></tr>@endforelse</tbody></table></div></div>
        @elseif($tab === 'transfers')
            <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.stock-transfers.ref_no') }}</th><th>{{ __('general.pages.stock-transfers.from_branch') }}</th><th>{{ __('general.pages.stock-transfers.to_branch') }}</th><th>{{ __('general.pages.products.qty') }}</th><th>{{ __('general.pages.stock-transfers.transfer_date') }}</th><th>{{ __('general.pages.stock-transfers.details_tab') }}</th></tr></thead><tbody>@forelse($recentTransferItems as $item)<tr><td>{{ $item->stockTransfer?->ref_no }}</td><td>{{ $item->stockTransfer?->fromBranch?->name }}</td><td>{{ $item->stockTransfer?->toBranch?->name }}</td><td>{{ $item->qty }}</td><td>{{ dateTimeFormat($item->stockTransfer?->transfer_date) }}</td><td class="text-nowrap">@if($item->stockTransfer)<a href="{{ route('admin.stocks.transfers.details', $item->stockTransfer->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20"><i class="fa fa-eye"></i></a>@endif</td></tr>@empty<tr><td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.products.no_transfer_records') }}</td></tr>@endforelse</tbody></table></div></div>
        @else
            <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.stock-taking.adjustment_id') }}</th><th>{{ __('general.pages.stock-taking.branch') }}</th><th>{{ __('general.pages.stock-taking.current_stock') }}</th><th>{{ __('general.pages.stock-taking.actual_stock') }}</th><th>{{ __('general.pages.stock-taking.difference') }}</th><th>{{ __('general.pages.stock-taking.details') }}</th></tr></thead><tbody>@forelse($recentAdjustments as $adj)<tr><td>{{ $adj->stock_taking_id }}</td><td>{{ $adj->stockTaking?->branch?->name }}</td><td>{{ round($adj->current_qty, 3) }}</td><td>{{ round($adj->actual_qty, 3) }}</td><td><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $adj->difference >= 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">{{ round($adj->difference, 3) }}</span></td><td class="text-nowrap">@if($adj->stockTaking)<a href="{{ route('admin.stocks.adjustments.details', $adj->stock_taking_id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20"><i class="fa fa-eye"></i></a>@endif</td></tr>@empty<tr><td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.products.no_adjustment_records') }}</td></tr>@endforelse</tbody></table></div></div>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>
