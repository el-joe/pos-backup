<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.ref_no') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $purchase->ref_no ?? __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.order_date') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($purchase->order_date) ?? __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.items_count') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $purchase->purchaseItems->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.total_quantity') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $actualQty }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.grand_total') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderGrandTotal ?? 0, true) }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchases.purchase_order_details') . ' #' . $id" :description="__('general.pages.purchases.purchase_details')" icon="fa fa-shopping-cart">
        <x-slot:head>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('activeTab', 'details')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'details' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-info-circle"></i>
                    {{ __('general.pages.purchases.details_tab') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'products')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'products' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-cubes"></i>
                    {{ __('general.pages.purchases.products_tab') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'expenses')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'expenses' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-credit-card"></i>
                    {{ __('general.pages.purchases.expenses_tab') }}
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
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.branch') }}</p>
                        <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $purchase->branch?->name ?? __('general.messages.n_a') }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.supplier') }}</p>
                        <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $purchase->supplier?->name ?? __('general.messages.n_a') }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.ref_no') }}</p>
                        <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $purchase->ref_no ?? __('general.messages.n_a') }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.order_date') }}</p>
                        <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($purchase->order_date) ?? __('general.messages.n_a') }}</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.items_count') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $purchase->purchaseItems->count() }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.total_quantity') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $actualQty }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.expenses.total_expense') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($purchase->expenses->sum('amount') ?? 0, true) }}</p>
                    </div>
                    <div class="rounded-3xl border border-brand-200 bg-brand-50 p-5 shadow-sm dark:border-brand-500/30 dark:bg-brand-500/10">
                        <p class="text-sm font-medium text-brand-700 dark:text-brand-200">{{ __('general.pages.purchases.grand_total') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-brand-900 dark:text-white">{{ currencyFormat($orderGrandTotal ?? 0, true) }}</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.subtotal_before_discount') }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderSubTotal ?? 0, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.after_discount') }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderTotalAfterDiscount ?? 0, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.tax_amount') }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderTaxAmount ?? 0, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.discount_percentage') }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $purchase->discount_type ? ($purchase->discount_value ?? 0) . ($purchase->discount_type === 'percentage' ? '%' : '') : __('general.messages.n_a') }}</p>
                    </div>
                </div>
            </div>
        @elseif($activeTab === 'products')
            <div class="p-5">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" style="min-width: 1500px;">
                        <thead>
                            <tr>
                                <th>{{ __('general.pages.purchases.product') }}</th>
                                <th>{{ __('general.pages.purchases.unit') }}</th>
                                <th>{{ __('general.pages.purchases.qty') }}</th>
                                <th>{{ __('general.pages.purchases.unit_price') }}</th>
                                <th>{{ __('general.pages.purchases.discount_percentage') }}</th>
                                <th>{{ __('general.pages.purchases.net_unit_cost') }}</th>
                                <th>{{ __('general.pages.purchases.total_net_cost') }}</th>
                                <th>{{ __('general.pages.purchases.tax_percentage') }}</th>
                                <th>{{ __('general.pages.purchases.subtotal_incl_tax') }}</th>
                                <th>{{ __('general.pages.purchases.extra_margin_percentage') }}</th>
                                <th>{{ __('general.pages.purchases.selling_price_per_unit') }}</th>
                                <th>{{ __('general.pages.purchases.grand_total') }}</th>
                                <th>{{ __('general.pages.purchases.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($purchase->purchaseItems as $item)
                                <tr>
                                    <td class="font-semibold">{{ $item?->product?->name }}</td>
                                    <td>{{ $item?->unit?->name ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $item->actual_qty }}</td>
                                    <td>{{ currencyFormat($item->purchase_price, true) }}</td>
                                    <td>{{ number_format($item->discount_percentage, 2) }}%</td>
                                    <td>{{ currencyFormat($item->unit_cost_after_discount, true) }}</td>
                                    <td>{{ currencyFormat($item->total_after_discount, true) }}</td>
                                    <td>{{ $item->tax_percentage ? number_format($item->tax_percentage, 2) : __('general.messages.n_a') }}%</td>
                                    <td>{{ currencyFormat($item->unit_amount_after_tax, true) }}</td>
                                    <td>{{ number_format($item->x_margin, 2) }}%</td>
                                    <td>{{ currencyFormat($item->total_after_x_margin, true) }}</td>
                                    <td>{{ currencyFormat($item->total_after_x_margin * $item->actual_qty, true) }}</td>
                                    <td>
                                        @if($item->actual_qty <= 0)
                                            <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-400 dark:border-slate-700 dark:text-slate-500" disabled>
                                                <i class="fa fa-check"></i> {{ __('general.pages.purchases.refund') }}
                                            </button>
                                        @else
                                            <button class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-700" wire:click="setCurrentItem({{ $item->id }})" data-toggle="modal" data-target="#refundModal">
                                                <i class="fa fa-undo"></i> {{ __('general.pages.purchases.refund') }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($activeTab === 'expenses')
            <div class="p-5">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('general.pages.purchases.description') }}</th>
                                <th>{{ __('general.pages.purchases.amount') }}</th>
                                <th>{{ __('general.pages.purchases.expense_date') }}</th>
                                <th>{{ __('general.pages.purchases.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($purchase->expenses ?? [] as $expense)
                                <tr>
                                    <td>{{ $expense->description ?? __('general.messages.n_a') }}</td>
                                    <td>{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                                    <td>{{ $expense->expense_date ? dateTimeFormat($expense->expense_date) : __('general.messages.n_a') }}</td>
                                    <td>
                                        <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="deleteExpenseConfirm({{ $expense->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
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
                    <h5 class="modal-title mx-auto"><i class="fa fa-undo"></i> {{ __('general.pages.purchases.refund_item') }}</h5>
                    <button type="button" class="close refund-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="refund-warning">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <p class="mb-1">{{ __('general.pages.purchases.about_to_refund') }}</p>
                            <strong class="refund-product-name">{{ $currentItem?->product?->name }}</strong>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="refundQty"><strong>{{ __('general.pages.purchases.quantity_to_refund') }}</strong></label>
                        <input type="number" class="form-control refund-input" id="refundQty" min="1" max="{{ $currentItem?->actual_qty ?? 1 }}" wire:model="refundedQty">
                        <small class="form-text text-muted">{{ __('general.pages.purchases.max_refundable') }} {{ $currentItem?->actual_qty ?? 1 }}</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('general.pages.purchases.cancel') }}</button>
                    @if ($currentItem)
                    <button type="button" class="btn btn-danger" wire:click="refundPurchaseItem">
                        <i class="fa fa-check"></i> {{ __('general.pages.purchases.confirm_refund') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
