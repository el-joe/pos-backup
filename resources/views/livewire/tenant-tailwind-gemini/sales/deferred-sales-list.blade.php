<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.deferred_sales')" icon="fa-clock-o">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('pos.create')
                <a class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" href="{{ route('admin.pos.deferred') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.sales.new_deferred_sale') }}
                </a>
                @endadminCan
                <a class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" href="{{ route('admin.sales.index') }}">
                    <i class="fa fa-list"></i> {{ __('general.pages.sales.all_sales') }}
                </a>
            </div>
        </x-slot:actions>
        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.invoice_number') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.customer') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.branch') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.total') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.due') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.sales.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($sales as $sale)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $sale->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $sale->invoice_number }}</td>
                        <td class="px-5 py-4">{{ $sale->customer?->name }}</td>
                        <td class="px-5 py-4">{{ $sale->branch?->name }}</td>
                        <td class="px-5 py-4">{{ currencyFormat($sale->grand_total_amount ?? 0, true) }}</td>
                        <td class="px-5 py-4"><span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">{{ currencyFormat($sale->due_amount ?? 0, true) }}</span></td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('sales.show')
                                    <a href="{{ route('admin.sales.details', $sale->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20" title="{{ __('general.pages.sales.details') }}"><i class="fa fa-pencil"></i></a>
                                @endadminCan
                                @adminCan('sales.pay')
                                    <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:hover:bg-emerald-500/20" wire:click="setCurrent({{ $sale->id }})" data-bs-toggle="modal" data-bs-target="#paymentModal" title="{{ __('general.pages.sales.save_payment') }}"><i class="fa fa-credit-card"></i></button>
                                @endadminCan
                                <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/20" onclick="if(confirm('{{ __('general.pages.sales.confirm_deliver_inventory_now') }}')) { @this.deliverInventory({{ $sale->id }}) }" title="{{ __('general.pages.sales.deliver_inventory') }}"><i class="fa fa-truck"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.no_deferred_sales_pending_delivery') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($sales->hasPages())
            <x-slot:footer>
                {{ $sales->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-[28px] border-0 shadow-2xl dark:bg-slate-900">
                <div class="bg-gradient-to-r from-brand-600 to-sky-500 px-6 py-5 text-white">
                    <div class="flex items-center justify-between gap-4">
                        <h5 class="text-lg font-semibold" id="paymentModalLabel">{{ __('general.pages.sales.payment_modal_title') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="space-y-6 p-6">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="paymentAmount" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.sales.amount') }}</label>
                            <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-950">
                                <span class="flex items-center border-r border-slate-200 px-3 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">{{ currency()->symbol }}</span>
                                <input type="number" class="min-w-0 flex-1 border-0 bg-transparent px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-0 dark:text-white dark:placeholder:text-slate-500" id="paymentAmount" wire:model="payment.amount" placeholder="{{ __('general.pages.sales.amount') }}">
                                <span class="flex items-center border-l border-slate-200 px-3 text-xs font-medium text-slate-500 dark:border-slate-700 dark:text-slate-400">{{ __('general.pages.sales.due') }}: <strong class="ms-1 text-rose-600 dark:text-rose-300">{{ number_format($current->due_amount ?? 0, 2) }}</strong></span>
                            </div>
                            @error('payment.amount')
                                <small class="mt-1 block text-sm text-rose-600 dark:text-rose-300">{{ $message }}</small>
                            @enderror
                        </div>

                        <div>
                            <label for="paymentMethod" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.sales.customer_account') }}</label>
                            <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">{{ __('general.pages.sales.select_account') }}</option>
                                @foreach (collect($current?->customer?->accounts ?? [])->filter() as $customerAccount)
                                    <option value="{{ data_get($customerAccount, 'id') }}">{{ data_get($customerAccount, 'paymentMethod.name') }} - {{ data_get($customerAccount, 'name') }}</option>
                                @endforeach
                            </select>
                            @error('payment.account_id')
                                <small class="mt-1 block text-sm text-rose-600 dark:text-rose-300">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="paymentNote" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.sales.note') }}</label>
                        <textarea class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" id="paymentNote" wire:model="payment.note" rows="3" placeholder="{{ __('general.pages.sales.optional_notes') }}"></textarea>
                        @error('payment.note')
                            <small class="mt-1 block text-sm text-rose-600 dark:text-rose-300">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="button" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700" wire:click="savePayment">
                        <i class="fa fa-check"></i> {{ __('general.pages.sales.save_payment') }}
                    </button>
                </div>

                <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-dismiss="modal">{{ __('general.pages.sales.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
