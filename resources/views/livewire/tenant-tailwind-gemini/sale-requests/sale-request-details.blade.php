<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.sale_request') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $request->quote_number }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.customer') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $request->customer?->name ?? __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.status') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $request->status?->label() ?? (string) $request->status }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.total') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($request->grand_total_amount ?? 0, true) }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.sale_requests.sale_request') . ': ' . $request->quote_number" icon="fa fa-file-text">
        <x-slot:head>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('activeTab', 'details')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'details' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-info-circle"></i>
                    {{ __('general.pages.sale_requests.request_details') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'items')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'items' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-cubes"></i>
                    {{ __('general.pages.sale_requests.items') }}
                </button>
            </div>
        </x-slot:head>

        @if($activeTab === 'details')
            <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.customer') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $request->customer?->name ?? __('general.messages.n_a') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.branch') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $request->branch?->name ?? __('general.messages.n_a') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.status') }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <span class="inline-flex rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold text-white dark:bg-white dark:text-slate-900">{{ $request->status?->label() ?? (string) $request->status }}</span>
                        <select class="w-full max-w-[220px] rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:change="updateStatus($event.target.value)">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" {{ ($request->status?->value ?? (string) $request->status) == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.request_date') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $request->request_date ? dateTimeFormat($request->request_date, true, false) : __('general.messages.n_a') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.valid_until') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $request->valid_until ? dateTimeFormat($request->valid_until, true, false) : __('general.messages.n_a') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.total') }}</p>
                    <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ currencyFormat($request->grand_total_amount ?? 0, true) }}</p>
                </div>

                @if($request->note)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 md:col-span-2 xl:col-span-3 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.note') }}</p>
                        <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-300">{{ $request->note }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="p-5">
                <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                        <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                            <tr>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.product') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.unit') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.qty') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.sell_price') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.taxable') }}</th>
                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($request->items as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $item->product?->name ?? $item->product_id }}</td>
                                    <td class="px-4 py-3">{{ $item->unit?->name ?? $item->unit_id }}</td>
                                    <td class="px-4 py-3">{{ $item->qty }}</td>
                                    <td class="px-4 py-3">{{ currencyFormat($item->sell_price, true) }}</td>
                                    <td class="px-4 py-3">{{ $item->taxable ? __('general.pages.sale_requests.yes') : __('general.pages.sale_requests.no') }}</td>
                                    <td class="px-4 py-3">{{ currencyFormat(($item->sell_price * $item->qty), true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>
