<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.sales.filters')" icon="fa fa-filter" :expanded="$collapseFilters">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.sales.invoice_no') }}</label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" placeholder="{{ __('general.pages.sales.search_placeholder') }}" wire:model.blur="filters.search">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.sales.due_filter') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.due_filter">
                    <option value="all">{{ __('general.pages.sales.due_filter_all') }}</option>
                    <option value="paid">{{ __('general.pages.sales.due_filter_paid') }}</option>
                    <option value="unpaid">{{ __('general.pages.sales.due_filter_unpaid') }}</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.sales.customer') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.customer_id">
                    <option value="">{{ __('general.pages.sales.all_customers') }}</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ ($filters['customer_id'] ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.sales.branch') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.branch_id">
                    <option value="">{{ __('general.pages.sales.all_branches_option') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end justify-start md:col-span-2 xl:col-span-4 xl:justify-end">
                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.sales.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.sales.selling_orders')" icon="fa fa-shopping-basket">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('sales.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-2 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.sales.export') }}
                    </button>
                @endadminCan
                @adminCan('pos.create')
                    <a class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" href="{{ route('admin.pos') }}">
                        <i class="fa fa-plus"></i> {{ __('general.pages.sales.new_selling_order') }}
                    </a>
                    <a class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" href="{{ route('admin.pos.deferred') }}">
                        <i class="fa fa-clock"></i> {{ __('general.titles.deferred_pos') }}
                    </a>
                    <a class="inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-3 py-2 text-sm font-medium text-amber-700 transition-colors hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/20 dark:focus:ring-offset-slate-900" href="{{ route('admin.sales.deferred') }}">
                        <i class="fa fa-truck"></i> {{ __('general.titles.deferred_sales') }}
                    </a>
                @endadminCan
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.invoice_no') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.customer') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.branch') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.total_amount') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.due_amount') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.sales.refund_status') }}</th>
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
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">{{ currencyFormat($sale->due_amount ?? 0, true) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-{{ $sale->refund_status->colorClass() }}">
                                {{ $sale->refund_status->label() }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('sales.show')
                                    <a href="{{ route('admin.sales.details', $sale->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20" title="{{ __('general.pages.sales.details') }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                @endadminCan
                                @adminCan('sales.pay')
                                    <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:hover:bg-emerald-500/20" wire:click="setCurrent({{ $sale->id }})" data-bs-toggle="modal" data-bs-target="#paymentModal" title="{{ __('general.pages.sales.save_payment') }}">
                                        <i class="fa fa-credit-card"></i>
                                    </button>
                                @endadminCan
                                @adminCan('sales.show-invoice')
                                    <a href="{{ route('sales.invoice', encodedData(['type' => '80mm','order_id'=>$sale->id, 'action' => 'print'])) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20" title="{{ __('general.pages.sales.print') }}" target="_blank">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <a href="{{ route('sales.invoice', encodedData(['type' => 'a4','order_id'=>$sale->id, 'action' => 'pdf'])) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/20" title="{{ __('general.pages.sales.export_pdf') }}" target="_blank">
                                        <i class="fa fa-file-pdf"></i>
                                    </a>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
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
                                <span class="flex items-center border-l border-slate-200 px-3 text-xs font-medium text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                    {{ __('general.pages.sales.due') ?? 'Due' }}:
                                    <strong class="ms-1 text-rose-600 dark:text-rose-300">{{ number_format($current->due_amount ?? 0, 2) }}</strong>
                                </span>
                            </div>
                            @error('payment.amount')
                                <small class="mt-1 block text-sm text-rose-600 dark:text-rose-300">{{ $message }}</small>
                            @enderror
                        </div>

                        <div>
                            <label for="paymentMethod" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.sales.customer_account') }}</label>
                            <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">{{ __('general.pages.sales.select_account') }}</option>
                                @foreach (($current?->customer?->accounts ?? []) as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->paymentMethod?->name }} - {{ $acc->name }}</option>
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

                    <div class="border-t border-slate-200 pt-6 dark:border-slate-800">
                        <h5 class="mb-4 text-base font-semibold text-slate-900 dark:text-white">{{ __('general.pages.sales.recent_payments') }}</h5>
                        <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                                <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.sales.date') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.sales.amount') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.sales.method') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.sales.note') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <?php
                                        $payments = $current?->transactions
                                            ? $current?->transactions->whereIn('type', [
                                                App\Enums\TransactionTypeEnum::SALE_PAYMENT,
                                                App\Enums\TransactionTypeEnum::SALE_PAYMENT_REFUND,
                                            ])->load('lines') : [];
                                    ?>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="px-4 py-3">{{ dateTimeFormat($payment->created_at,true,false) }}</td>
                                            <td class="px-4 py-3"><span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">{{ currencyFormat($payment->amount, true) }}</span></td>
                                            <td class="px-4 py-3">{{ $payment->account() ? ($payment->account('credit')->paymentMethod?->name ? $payment->account('credit')->paymentMethod?->name .' - '  : '' ) . $payment->account('credit')->name : __('general.messages.n_a') }}</td>
                                            <td class="px-4 py-3">{{ $payment->note }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.sales.no_payments') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('general.pages.sales.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
