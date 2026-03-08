<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchase_requests.request_no') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $data['request_number'] ?? '---' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchase_requests.products') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ count($orderProducts ?? []) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchase_requests.tax_percentage') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($data['tax_percentage'] ?? 0, 2) }}%</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchase_requests.status') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">
                @php
                    $currentStatus = collect($statuses)->firstWhere('value', $data['status'] ?? null);
                @endphp
                {{ $currentStatus?->label() ?? __('general.pages.purchase_requests.none') }}
            </p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchase_requests.request_details')" icon="fa fa-file-text">
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div>
                <label class="form-label">{{ __('general.pages.purchase_requests.branch') }}</label>
                @if(admin()->branch_id == null)
                    <select class="form-select select2" name="data.branch_id">
                        <option value="">{{ __('general.pages.purchase_requests.select_branch') }}</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id'] ?? 0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                @endif
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.purchase_requests.supplier_optional') }}</label>
                <select class="form-select select2" name="data.supplier_id">
                    <option value="">{{ __('general.pages.purchase_requests.none') }}</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ ($data['supplier_id'] ?? 0) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.purchase_requests.request_no') }}</label>
                <input type="text" class="form-control" wire:model="data.request_number" disabled>
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.purchase_requests.request_date') }}</label>
                <input type="date" class="form-control" wire:model="data.request_date">
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.purchase_requests.status') }}</label>
                <select class="form-select select2" name="data.status">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.purchase_requests.discount_type') }}</label>
                <select class="form-select select2" name="data.discount_type">
                    <option value="">{{ __('general.pages.purchase_requests.none') }}</option>
                    <option value="fixed">{{ __('general.pages.purchase_requests.fixed') }}</option>
                    <option value="percentage">{{ __('general.pages.purchase_requests.percentage') }}</option>
                </select>
            </div>

            @if($data['discount_type'] ?? false)
                <div>
                    <label class="form-label">{{ __('general.pages.purchase_requests.discount_value') }}</label>
                    <input type="number" step="0.01" class="form-control" wire:model="data.discount_value">
                </div>
            @endif

            <div>
                <label class="form-label">{{ __('general.pages.purchase_requests.tax_percentage') }}</label>
                <input type="number" step="0.01" class="form-control" wire:model="data.tax_percentage">
            </div>

            <div class="md:col-span-2 xl:col-span-3">
                <label class="form-label">{{ __('general.pages.purchase_requests.note') }}</label>
                <textarea class="form-control" rows="2" wire:model="data.note"></textarea>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchase_requests.products')" :description="__('general.pages.purchase_requests.search_product_placeholder')" icon="fa fa-cubes">
        <x-slot:head>
            <div class="grid gap-4 md:grid-cols-[minmax(0,28rem)_auto] md:items-end">
                <div>
                    <label for="product_search" class="form-label">{{ __('general.pages.purchase_requests.product') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input
                            type="text"
                            id="product_search"
                            class="form-control"
                            placeholder="{{ __('general.pages.purchase_requests.search_product_placeholder') }}"
                            wire:model.live.debounce.800ms="product_search"
                            x-data
                            @reset-search-input.window="$el.value=''"
                        >
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-300">
                    {{ __('general.pages.purchase_requests.products') }}: <span class="font-semibold text-slate-900 dark:text-white">{{ count($orderProducts ?? []) }}</span>
                </div>
            </div>
        </x-slot:head>

        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" style="min-width: 1300px;">
                    <thead>
                        <tr>
                            <th>{{ __('general.pages.purchase_requests.product') }}</th>
                            <th style="width: 220px;">{{ __('general.pages.purchase_requests.unit') }}</th>
                            <th style="width: 120px;">{{ __('general.pages.purchase_requests.qty') }}</th>
                            <th style="width: 160px;">{{ __('general.pages.purchase_requests.purchase_price') }}</th>
                            <th style="width: 160px;">{{ __('general.pages.purchase_requests.discount_percentage') }}</th>
                            <th style="width: 160px;">{{ __('general.pages.purchase_requests.tax_percentage_short') }}</th>
                            <th style="width: 140px;">{{ __('general.pages.purchase_requests.x_margin') }}</th>
                            <th style="width: 160px;">{{ __('general.pages.purchase_requests.sell_price') }}</th>
                            <th style="width: 90px;">{{ __('general.pages.purchase_requests.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orderProducts as $index => $product)
                            <tr>
                                <td class="fw-semibold">{{ $product['name'] }}</td>
                                <td>
                                    <select class="form-select select2" name="orderProducts.{{ $index }}.unit_id">
                                        @foreach ($product['units'] ?? [] as $unit)
                                            <option value="{{ $unit->id }}" {{ ($product['unit_id'] ?? 0) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="1" min="1" class="form-control" wire:model.live="orderProducts.{{ $index }}.qty">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.purchase_price">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.discount_percentage">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.tax_percentage">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.x_margin">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.sell_price">
                                </td>
                                <td class="text-center">
                                    <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="deleteProduct({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.purchase_requests.no_products_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex justify-end">
                <button class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" wire:click="saveRequest">
                    <i class="fa fa-save"></i> {{ __('general.pages.purchase_requests.save_request') }}
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
