<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.purchases.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchases.ref_no') }}</label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" placeholder="{{ __('general.pages.purchases.search_placeholder') }}" wire:model.blur="filters.ref_no">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchases.due_filter') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.due_filter">
                    <option value="all">{{ __('general.pages.purchases.due_filter_all') }}</option>
                    <option value="paid">{{ __('general.pages.purchases.due_filter_paid') }}</option>
                    <option value="unpaid">{{ __('general.pages.purchases.due_filter_unpaid') }}</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchases.supplier') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.supplier_id">
                    <option value="">{{ __('general.pages.purchases.all_suppliers') }}</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ ($filters['supplier_id'] ?? '') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchases.branch') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.branch_id">
                    <option value="">{{ __('general.pages.purchases.all_branches_option') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchases.status') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.status">
                    <option value="">{{ __('general.pages.purchases.all_statuses') }}</option>
                    @foreach (App\Enums\PurchaseStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}" {{ ($filters['status'] ?? '') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 xl:col-span-5 flex items-end justify-end">
                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.purchases.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchases.purchase_orders')" icon="fa-shopping-cart">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('purchases.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.purchases.export') }}
                    </button>
                @endadminCan
                @adminCan('purchases.create')
                    <a href="{{ route('admin.purchases.add') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-plus"></i> {{ __('general.pages.purchases.new_purchase_order') }}
                    </a>
                    <a href="{{ route('admin.purchases.deferred') }}" class="inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-3 py-1.5 text-sm font-medium text-amber-700 transition-colors hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/20 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-truck"></i> {{ __('general.titles.deferred_purchases') }}
                    </a>
                @endadminCan
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchases.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchases.ref_no') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchases.supplier') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchases.branch') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchases.status') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchases.total_amount') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchases.due_amount') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchases.refund_status') }}</th>
                    <th class="px-5 py-3 text-right font-semibold text-nowrap">{{ __('general.pages.purchases.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($purchases as $purchase)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $purchase->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $purchase->ref_no }}</td>
                        <td class="px-5 py-4">{{ $purchase->supplier?->name }}</td>
                        <td class="px-5 py-4">{{ $purchase->branch?->name }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-{{ $purchase->status->colorClass() }}">{{ $purchase->status->label() }}</span>
                        </td>
                        <td class="px-5 py-4">{{ currencyFormat($purchase->total_amount ?? 0, true) }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $purchase->due_amount > 0 ? 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' }}">{{ currencyFormat($purchase->due_amount ?? 0, true) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-{{ $purchase->refund_status->colorClass() }}">{{ $purchase->refund_status->label() }}</span>
                        </td>
                        <td class="px-5 py-4 text-right text-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('purchases.show')
                                    <a href="{{ route('admin.purchases.details', $purchase->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" title="{{ __('general.pages.purchases.details') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                @endadminCan
                                @adminCan('purchases.delete')
                                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-600 transition-colors hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-500/10" wire:click="setCurrent({{ $purchase->id }})" data-bs-toggle="modal" data-bs-target="#paymentModal" title="{{ __('general.pages.purchases.save_payment') }}">
                                        <i class="fa fa-credit-card"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($purchases->hasPages())
            <x-slot:footer>
                {{ $purchases->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content overflow-hidden rounded-[28px] border-0 shadow-2xl dark:bg-slate-900">
                <div class="bg-gradient-to-r from-brand-600 to-sky-500 px-6 py-5 text-white">
                    <div class="flex items-center justify-between gap-4">
                        <h5 class="text-lg font-semibold" id="paymentModalLabel">{{ __('general.pages.purchases.payment_modal_title') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="space-y-6 p-6">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="paymentAmount" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchases.amount') }}</label>
                            <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-950">
                                <span class="flex items-center border-r border-slate-200 px-3 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">{{ currency()->symbol }}</span>
                                <input type="number" class="min-w-0 flex-1 border-0 bg-transparent px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-0 dark:text-white dark:placeholder:text-slate-500" id="paymentAmount" wire:model="payment.amount" placeholder="{{ __('general.pages.purchases.amount') }}">
                                <span class="flex items-center border-l border-slate-200 px-3 text-xs font-medium text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                    {{ __('general.pages.purchases.due_amount') }}:
                                    <strong class="ms-1 text-rose-600 dark:text-rose-300">{{ number_format($current->due_amount ?? 0, 2) }}</strong>
                                </span>
                            </div>
                            @error('payment.amount')
                                <small class="mt-1 block text-sm text-rose-600 dark:text-rose-300">{{ $message }}</small>
                            @enderror
                        </div>

                        <div>
                            <label for="paymentMethod" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchases.supplier_account') }}</label>
                            <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-500" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">{{ __('general.pages.purchases.select_account') }}</option>
                                @foreach (($current?->supplier?->accounts ?? []) as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->paymentMethod?->name }} - {{ $acc->name }}</option>
                                @endforeach
                            </select>
                            @error('payment.account_id')
                                <small class="mt-1 block text-sm text-rose-600 dark:text-rose-300">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="paymentNote" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchases.note') }}</label>
                        <textarea class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" id="paymentNote" wire:model="payment.note" rows="3" placeholder="{{ __('general.pages.purchases.optional_notes') }}"></textarea>
                        @error('payment.note')
                            <small class="mt-1 block text-sm text-rose-600 dark:text-rose-300">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="button" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700" wire:click="savePayment">
                        <i class="fa fa-check"></i> {{ __('general.pages.purchases.save_payment') }}
                    </button>

                    <div class="border-t border-slate-200 pt-6 dark:border-slate-800">
                        <h5 class="mb-4 text-base font-semibold text-slate-900 dark:text-white">{{ __('general.pages.purchases.recent_payments') }}</h5>
                        <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                                <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.purchases.date') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.purchases.amount') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.purchases.method') }}</th>
                                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.purchases.note') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <?php
                                        $payments = $current?->transactions
                                            ? $current?->transactions->whereIn('type', [
                                                App\Enums\TransactionTypeEnum::PURCHASE_PAYMENT,
                                                App\Enums\TransactionTypeEnum::PURCHASE_PAYMENT_REFUND,
                                            ])->load('lines') : [];
                                    ?>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="px-4 py-3">{{ dateTimeFormat($payment->created_at) }}</td>
                                            <td class="px-4 py-3"><span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">{{ currencyFormat($payment->amount, true) }}</span></td>
                                            <td class="px-4 py-3">{{ $payment->account() ?  ($payment->account()->paymentMethod?->name ? $payment->account()->paymentMethod?->name .' - '  : '' ) . $payment->account()->name : __('general.messages.n_a') }}</td>
                                            <td class="px-4 py-3">{{ $payment->note }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.no_payments') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('general.pages.purchases.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
