<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.quote_no') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $data['quote_number'] ?? '---' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.products') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ count($products ?? []) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.tax_percentage') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($data['tax_percentage'] ?? 0, 2) }}%</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.status') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">
                @php
                    $currentStatus = collect($statuses)->firstWhere('value', $data['status'] ?? null);
                @endphp
                {{ $currentStatus?->label() ?? __('general.pages.sale_requests.none') }}
            </p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.sale_requests.request_details')" icon="fa fa-file-text">
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.branch') }}</label>
                @if(admin()->branch_id == null)
                    <select class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white" name="data.branch_id">
                        <option value="">{{ __('general.pages.sale_requests.select_branch') }}</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id'] ?? 0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="text" class="block w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" value="{{ admin()->branch?->name }}" disabled>
                @endif
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.customer') }}</label>
                <select class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white" name="data.customer_id">
                    <option value="">{{ __('general.pages.sale_requests.select_customer') }}</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ ($data['customer_id'] ?? 0) == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.quote_no') }}</label>
                <input type="text" class="block w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" wire:model="data.quote_number" disabled>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.request_date') }}</label>
                <input type="date" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.request_date">
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.valid_until') }}</label>
                <input type="date" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.valid_until">
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.status') }}</label>
                <select class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white" name="data.status">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.discount_type') }}</label>
                <select class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white" name="data.discount_type">
                    <option value="">{{ __('general.pages.sale_requests.none') }}</option>
                    <option value="fixed">{{ __('general.pages.sale_requests.fixed') }}</option>
                    <option value="percentage">{{ __('general.pages.sale_requests.percentage') }}</option>
                </select>
            </div>

            @if($data['discount_type'] ?? false)
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.discount_value') }}</label>
                    <input type="number" step="0.01" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.discount_value">
                </div>
            @endif

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.tax_percentage') }}</label>
                <input type="number" step="0.01" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.tax_percentage">
            </div>

            <div class="md:col-span-2 xl:col-span-3">
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.note') }}</label>
                <textarea class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" rows="2" wire:model="data.note"></textarea>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.sale_requests.products')" :description="__('general.pages.sale_requests.search_product_placeholder')" icon="fa fa-cubes">
        <x-slot:head>
            <div class="grid gap-4 md:grid-cols-[minmax(0,28rem)_auto] md:items-end">
                <div>
                    <label for="product_search" class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.product') }}</label>
                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <span class="flex items-center border-r border-slate-200 px-3 text-slate-500 dark:border-slate-700 dark:text-slate-400"><i class="fa fa-search"></i></span>
                        <input
                            type="text"
                            id="product_search"
                            class="min-w-0 flex-1 bg-transparent px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none dark:text-white dark:placeholder:text-slate-500"
                            placeholder="{{ __('general.pages.sale_requests.search_product_placeholder') }}"
                            wire:model.live.debounce.800ms="product_search"
                            x-data
                            @reset-search-input.window="$el.value=''"
                        >
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-300">
                    {{ __('general.pages.sale_requests.products') }}: <span class="font-semibold text-slate-900 dark:text-white">{{ count($products ?? []) }}</span>
                </div>
            </div>
        </x-slot:head>

        <div class="p-5">
            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.product') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.unit') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.qty') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.sell_price') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('general.pages.sale_requests.taxable') }}</th>
                            <th class="px-4 py-3 text-right font-semibold">{{ __('general.pages.sale_requests.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($products as $index => $product)
                            <tr>
                                <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">{{ $product['name'] }}</td>
                                <td class="px-4 py-4">
                                    <select class="select2 block w-full min-w-[180px] rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white" name="products.{{ $index }}.unit_id">
                                        @foreach ($product['units'] ?? [] as $unit)
                                            <option value="{{ $unit->id }}" {{ ($product['unit_id'] ?? 0) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" step="1" min="1" class="block w-24 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model.live="products.{{ $index }}.qty">
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" step="0.01" min="0" class="block w-32 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model.live="products.{{ $index }}.sell_price">
                                </td>
                                <td class="px-4 py-4">
                                    <select class="block w-24 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model.live="products.{{ $index }}.taxable">
                                        <option value="1">{{ __('general.pages.sale_requests.yes') }}</option>
                                        <option value="0">{{ __('general.pages.sale_requests.no') }}</option>
                                    </select>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="deleteProduct({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.sale_requests.no_products_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex justify-end">
                <button class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" wire:click="saveRequest">
                    <i class="fa fa-save"></i> {{ __('general.pages.sale_requests.save_request') }}
                </button>
            </div>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
    <script>
        window.addEventListener('reset-search-input', event => {
            const input = document.getElementById('product_search');
            if (input) {
                input.value = '';
            }
        });
    </script>
@endpush
