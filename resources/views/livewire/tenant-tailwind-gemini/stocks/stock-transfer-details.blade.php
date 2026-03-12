<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.ref_no') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $stockTransfer->ref_no ?? __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.transfer_date') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($stockTransfer->transfer_date, true, false) ?? __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.status') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $stockTransfer->status->label() ?? __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.products_tab') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $stockTransfer->items->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.expenses_tab') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ collect($stockTransfer->expenses ?? [])->count() }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.stock-transfers') . ' #' . $id" :description="__('general.pages.stock-transfers.stock_transfer_details')" icon="fa fa-exchange-alt">
        <x-slot:head>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('activeTab', 'details')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'details' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-info-circle"></i>
                    {{ __('general.pages.stock-transfers.details_tab') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'products')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'products' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-cubes"></i>
                    {{ __('general.pages.stock-transfers.products_tab') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'expenses')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'expenses' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-money-bill-wave"></i>
                    {{ __('general.pages.stock-transfers.expenses_tab') }}
                </button>
            </div>
        </x-slot:head>

        @if($activeTab === 'details')
            <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.from_branch') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $stockTransfer->fromBranch?->name ?? __('general.messages.n_a') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.to_branch') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $stockTransfer->toBranch?->name ?? __('general.messages.n_a') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.status') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $stockTransfer->status->label() ?? __('general.messages.n_a') }}</p>
                </div>
            </div>
        @elseif($activeTab === 'products')
            <div class="p-5">
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                        <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                            <tr>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-transfers.product') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-transfers.unit') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-transfers.qty') }}</th>
                                <th class="px-4 py-3 text-right font-semibold">{{ __('general.pages.stock-transfers.unit_price') }}</th>
                                <th class="px-4 py-3 text-right font-semibold">{{ __('general.pages.stock-transfers.selling_price') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($stockTransfer->items as $item)
                                <tr>
                                    <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">{{ $item?->product?->name }}</td>
                                    <td class="px-4 py-4">{{ $item?->unit?->name ?? __('general.messages.n_a') }}</td>
                                    <td class="px-4 py-4">{{ $item->qty }}</td>
                                    <td class="px-4 py-4 text-right">{{ currencyFormat($item->unit_cost, true) }}</td>
                                    <td class="px-4 py-4 text-right">{{ currencyFormat($item->sell_price, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="p-5">
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                        <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                            <tr>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-transfers.description') }}</th>
                                <th class="px-4 py-3 text-right font-semibold">{{ __('general.pages.stock-transfers.amount') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-transfers.expense_date') }}</th>
                                <th class="px-4 py-3 text-right font-semibold">{{ __('general.pages.stock-transfers.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($stockTransfer->expenses ?? [] as $expense)
                                <tr>
                                    <td class="px-4 py-4">{{ $expense->description ?? $expense->note ?? __('general.messages.n_a') }}</td>
                                    <td class="px-4 py-4 text-right">{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                                    <td class="px-4 py-4">{{ $expense->expense_date ? carbon($expense->expense_date)->format('Y-m-d') : __('general.messages.n_a') }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="deleteExpenseConfirm({{ $expense->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    <div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content refund-modal-content">
                <div class="modal-header refund-modal-header">
                    <h5 class="modal-title mx-auto"><i class="fa fa-undo"></i> {{ __('general.pages.stock-transfers.refund_item') }}</h5>
                    <button type="button" class="close refund-close" data-bs-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="refund-warning">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <p class="mb-1">{{ __('general.pages.stock-transfers.refund_warning') }}</p>
                            <strong class="refund-product-name">{{ $currentItem?->product?->name }}</strong>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label><strong>{{ __('general.pages.stock-transfers.quantity_to_refund') }}</strong></label>
                        <input type="number" min="1" max="{{ $currentItem?->actual_qty ?? 1 }}" wire:model="refundedQty" class="refund-input mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        <small class="mt-2 block text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.max', ['qty' => $currentItem?->actual_qty ?? 1]) }}</small>
                    </div>
                </div>

                <div class="modal-footer justify-content-end gap-2">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-dismiss="modal">{{ __('general.pages.stock-transfers.cancel') }}</button>

                    @if ($currentItem)
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700" wire:click="refundPurchaseItem">
                        <i class="fa fa-check"></i> {{ __('general.pages.stock-transfers.confirm_refund') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
