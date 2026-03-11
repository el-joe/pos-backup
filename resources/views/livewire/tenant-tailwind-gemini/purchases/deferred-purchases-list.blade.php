<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.titles.deferred_purchases') }}</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $purchases->total() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 md:col-span-2 xl:col-span-2">
            <div class="flex flex-wrap gap-2 sm:justify-end">
                @adminCan('purchases.create')
                <a href="{{ route('admin.purchases.add', ['is_deferred' => 1]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700"><i class="fa fa-plus"></i> {{ __('general.pages.purchases.add_deferred_purchase') }}</a>
                @endadminCan
                <a href="{{ route('admin.purchases.list') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"><i class="fa fa-list"></i> {{ __('general.pages.purchases.all_purchases') }}</a>
            </div>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.deferred_purchases')" icon="fa fa-clock">
        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('general.pages.purchases.id') }}</th>
                            <th>{{ __('general.pages.purchases.ref_no') }}</th>
                            <th>{{ __('general.pages.purchases.supplier') }}</th>
                            <th>{{ __('general.pages.purchases.branch') }}</th>
                            <th>{{ __('general.pages.purchases.total_amount') }}</th>
                            <th>{{ __('general.pages.purchases.due_amount') }}</th>
                            <th class="text-nowrap">{{ __('general.pages.purchases.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->id }}</td>
                                <td>{{ $purchase->ref_no }}</td>
                                <td>{{ $purchase->supplier?->name }}</td>
                                <td>{{ $purchase->branch?->name }}</td>
                                <td>{{ currencyFormat($purchase->total_amount ?? 0, true) }}</td>
                                <td><span class="inline-flex rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">{{ currencyFormat($purchase->due_amount ?? 0, true) }}</span></td>
                                <td class="text-nowrap">
                                    @adminCan('purchases.show')
                                    <a href="{{ route('admin.purchases.details', $purchase->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20" title="{{ __('general.pages.purchases.details') }}"><i class="fa fa-pencil"></i></a>
                                    @endadminCan
                                    <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/20" onclick="if(confirm('{{ __('general.pages.purchases.confirm_receive_inventory_now') }}')) { @this.receiveInventory({{ $purchase->id }}) }" title="{{ __('general.pages.purchases.receive_inventory') }}"><i class="fa fa-truck"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.no_deferred_purchases_pending_receipt') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-slot:footer>
            <div class="flex justify-center">{{ $purchases->links('pagination::default5') }}</div>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
