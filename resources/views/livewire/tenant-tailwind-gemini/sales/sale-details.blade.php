<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.invoice_number') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">#{{ $order->invoice_number }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.order_date') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($order->created_at, true, false) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.items_count') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $itemsCount }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.paid') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($paid, true) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.due') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat(($grandTotal - $paid), true) }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.sales.sale_order_details') . ' #' . $order->id" :description="__('general.pages.sales.sale_details')" icon="fa fa-file-text">
        <x-slot:head>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('activeTab', 'details')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'details' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-info-circle"></i>
                    {{ __('general.pages.sales.details_tab') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'products')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'products' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-cubes"></i>
                    {{ __('general.pages.sales.products_tab') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'transactions')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'transactions' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-exchange"></i>
                    {{ __('general.pages.sales.transactions_tab') }}
                </button>
            </div>
        </x-slot:head>

        @if($activeTab === 'details')
            <div class="space-y-6 p-5">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.customer') }}</p>
                        <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $order->customer->name ?? __('general.messages.n_a') }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.branch') }}</p>
                        <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $order->branch->name ?? __('general.messages.n_a') }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.invoice_number') }}</p>
                        <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">#{{ $order->invoice_number }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.order_date') }}</p>
                        <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($order->created_at, true, false) }}</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.items_count') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $itemsCount }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.total_quantity') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $totalQty }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.subtotal') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($subTotal, true) }}</p>
                    </div>
                    <div class="rounded-3xl border border-brand-200 bg-brand-50 p-5 shadow-sm dark:border-brand-500/30 dark:bg-brand-500/10">
                        <p class="text-sm font-medium text-brand-700 dark:text-brand-200">{{ __('general.pages.sales.grand_total') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-brand-900 dark:text-white">{{ currencyFormat($grandTotal, true) }}</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.discount') }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($totalDiscount, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.tax') }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($totalTax, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.paid') }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($paid, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.due') }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat(($grandTotal - $paid), true) }}</p>
                    </div>
                </div>
            </div>
        @elseif($activeTab === 'products')
            <div class="p-5">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('general.pages.sales.product') }}</th>
                                <th>{{ __('general.pages.sales.quantity') }}</th>
                                <th>{{ __('general.pages.sales.refunded') }}</th>
                                <th>{{ __('general.pages.sales.unit_price') }}</th>
                                <th>{{ __('general.pages.sales.total') }}</th>
                                <th>{{ __('general.pages.sales.refund_status') }}</th>
                                <th>{{ __('general.pages.sales.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->saleItems as $item)
                                <tr>
                                    <td class="font-semibold">{{ $item->product?->name }} - {{ $item->unit?->name }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->refunded_qty }}</td>
                                    <td>{{ currencyFormat($item->sell_price, true) }}</td>
                                    <td>{{ currencyFormat($item->total, true) }}</td>
                                    <td>
                                        @if($item->actual_qty <= 0)
                                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">{{ __('general.pages.sales.fully_refunded') }}</span>
                                        @elseif($item->actual_qty < $item->qty)
                                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">{{ __('general.pages.sales.partially_refunded') }}</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">{{ __('general.pages.sales.not_refunded') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->actual_qty <= 0)
                                            <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-400 dark:border-slate-700 dark:text-slate-500" disabled>
                                                <i class="fa fa-check"></i> {{ __('general.pages.sales.refund') }}
                                            </button>
                                        @else
                                            <button class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-700" wire:click="setCurrentItem({{ $item->id }})" data-toggle="modal" data-target="#refundModal">
                                                <i class="fa fa-undo"></i> {{ __('general.pages.sales.refund') }}
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
        @else
            <div class="p-5">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('general.pages.purchases.transaction_lines.id') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.transaction_id') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.type') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.branch') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.reference') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.note') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.date') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.account') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.debit') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.credit') }}</th>
                                <th>{{ __('general.pages.purchases.transaction_lines.created_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totals = ['debit' => 0, 'credit' => 0]; @endphp
                            @forelse($transactionLines as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->transaction_id ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $transaction->type ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $transaction->branch ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $transaction->reference ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $transaction->note ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $transaction->date ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $transaction->account ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $transaction->line_type == 'debit' ? $transaction->amount : currencyFormat('0', true) }}</td>
                                    <td>{{ $transaction->line_type == 'credit' ? $transaction->amount : currencyFormat('0', true) }}</td>
                                    <td>{{ $transaction->created_at ?? __('general.messages.n_a') }}</td>
                                </tr>
                                @php
                                    if ($transaction->line_type == 'debit') {
                                        $totals['debit'] += $transaction->amount_raw;
                                    } elseif ($transaction->line_type == 'credit') {
                                        $totals['credit'] += $transaction->amount_raw;
                                    }
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="11" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                                </tr>
                            @endforelse
                            @if(count($transactionLines ?? []))
                                <tr class="bg-slate-50 font-semibold dark:bg-slate-800/60">
                                    <td colspan="8" class="text-{{ app()->getLocale() == 'ar' ? 'end' : 'start' }}">{{ __('general.pages.purchases.transaction_lines.total') }}</td>
                                    <td>{{ currencyFormat($totals['debit'], true) }}</td>
                                    <td>{{ currencyFormat($totals['credit'], true) }}</td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    <div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-labelledby="refundModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content refund-modal-content">
                <div class="modal-header refund-modal-header">
                    <h5 class="modal-title mx-auto"><i class="fa fa-undo"></i> {{ __('general.pages.sales.refund_item') }}</h5>
                    <button type="button" class="close refund-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="refund-warning">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <p class="mb-1">{{ __('general.pages.sales.about_to_refund') }}</p>
                            <strong class="refund-product-name">{{ $currentItem?->product?->name }} - {{ $currentItem?->unit?->name }}</strong>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="refundQty"><strong>{{ __('general.pages.sales.quantity_to_refund') }}</strong></label>
                        <input type="number" class="form-control refund-input" id="refundQty" min="1" max="{{ $currentItem?->actual_qty ?? 1 }}" wire:model="refundedQty">
                        <small class="form-text text-muted">{{ __('general.pages.sales.max_refundable') }} {{ $currentItem?->actual_qty ?? 1 }}</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('general.pages.sales.cancel') }}</button>
                    @if ($currentItem)
                    <button type="button" class="btn btn-danger" wire:click="refundSaleItem">
                        <i class="fa fa-check"></i> {{ __('general.pages.sales.confirm_refund') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
