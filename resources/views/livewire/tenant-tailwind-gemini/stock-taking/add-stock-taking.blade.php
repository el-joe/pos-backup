<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.add_stock_take') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $data['date'] ?? now()->format('Y-m-d') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.branch') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ admin()->branch?->name ?? collect($branches ?? [])->firstWhere('id', $data['branch_id'] ?? null)?->name ?? __('general.messages.n_a') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.products') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ collect($stocks ?? [])->flatten(1)->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.note') }}</p>
            <p class="mt-3 text-sm font-semibold text-slate-900 dark:text-white">{{ $data['note'] ?? __('general.messages.n_a') }}</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.45fr]">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stock-taking.details')" icon="fa fa-info-circle">
            <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-1">
                <div class="space-y-2">
                    <label for="branch_id" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.branch') }}</label>
                    @if(admin()->branch_id == null)
                        <div class="flex gap-2">
                            <select id="branch_id" name="data.branch_id" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                <option value="">{{ __('general.pages.stock-taking.select_branch') }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($data['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                        </div>
                    @else
                        <input type="text" class="block w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" value="{{ admin()->branch?->name }}" disabled>
                    @endif
                </div>

                <div class="space-y-2">
                    <label for="date" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.date') }}</label>
                    <input type="date" id="date" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.date">
                </div>

                <div class="space-y-2">
                    <label for="note" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.note') }}</label>
                    <textarea id="note" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.note" placeholder="{{ __('general.pages.stock-taking.optional_note') }}"></textarea>
                </div>
            </div>
        </x-tenant-tailwind-gemini.table-card>

        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stock-taking.products')" icon="fa fa-cubes">
            <x-slot:head>
                @if($data['branch_id'] ?? false)
                    <div>
                        <label for="product_search" class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-taking.product') }}</label>
                        <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                            <span class="flex items-center border-r border-slate-200 px-3 text-slate-500 dark:border-slate-700 dark:text-slate-400"><i class="fa fa-search"></i></span>
                            <input
                                type="text"
                                class="min-w-0 flex-1 bg-transparent px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none dark:text-white dark:placeholder:text-slate-500"
                                id="product_search"
                                placeholder="{{ __('general.pages.stock-taking.search_product_placeholder') }}"
                                onkeydown="productSearchEvent(event)"
                            >
                        </div>
                    </div>
                @endif
            </x-slot:head>

            <div class="p-5">
                @if($data['branch_id'] ?? false)
                    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                            <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-taking.product') }}</th>
                                    <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-taking.unit') }}</th>
                                    <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-taking.current_stock') }}</th>
                                    <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-taking.actual_stock') }}</th>
                                    <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-taking.difference') }}</th>
                                    <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-taking.total_cost') }}</th>
                                    <th class="px-4 py-3 font-semibold">{{ __('general.pages.stock-taking.status') }}</th>
                                    <th class="px-4 py-3 text-right font-semibold">{{ __('general.pages.stock-taking.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($stocks as $stock)
                                    @foreach($stock as $unit)
                                        <tr>
                                            <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">{{ $unit['product_name'] }}</td>
                                            <td class="px-4 py-4">{{ $unit['unit_name'] }}</td>
                                            <td class="px-4 py-4">{{ $unit['current_stock'] }}</td>
                                            <td class="px-4 py-4">
                                                <input type="number" class="block w-28 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" id="countedStock_{{ $unit['product_id'] }}_{{ $unit['unit_id'] }}" min="0" wire:model.lazy="data.countedStock.{{ $unit['product_id'] }}.{{ $unit['unit_id'] }}">
                                            </td>
                                            <td class="px-4 py-4">
                                                @php
                                                    $currentStock = $unit['current_stock'];
                                                    $actualStock = $data['countedStock'][$unit['product_id']][$unit['unit_id']] ?? 0;
                                                    $difference = $actualStock - $currentStock;

                                                    if($difference > 0) {
                                                        $badgeClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300';
                                                        $status = __('general.pages.stock-taking.surplus');
                                                        $sign = '+';
                                                    } elseif($difference < 0) {
                                                        $badgeClass = 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300';
                                                        $status = __('general.pages.stock-taking.shortage');
                                                        $sign = '';
                                                    } else {
                                                        $badgeClass = 'bg-slate-100 text-slate-700 dark:bg-slate-700/60 dark:text-slate-200';
                                                        $status = __('general.pages.stock-taking.no_change');
                                                        $sign = '';
                                                    }
                                                @endphp
                                                {{ $sign }}{{ $difference }}
                                            </td>
                                            <td class="px-4 py-4">{{ currencyFormat($unit['unit_cost'] * $difference, true) }}</td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $status }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                <button class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-700" wire:click="removeProductStock({{ $unit['product_id'] }}, {{ $unit['unit_id'] }})">
                                                    <i class="fa fa-trash"></i> {{ __('general.pages.stock-taking.remove') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-700 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-200">
                        {{ __('general.pages.stock-taking.select_branch_to_view_products') }}
                    </div>
                @endif
            </div>

            <x-slot:footer>
                <div class="flex justify-end">
                    <button class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50" wire:click="save" @if(!($data['branch_id'] ?? false) || count($stocks) == 0) disabled @endif>
                        <i class="fa fa-save"></i> {{ __('general.pages.stock-taking.save_stock_take') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('reset-search-input', event => {
        const input = document.getElementById('product_search');
        if (input) input.value = '';
    });

    function productSearchEvent(event) {
        if (event.key === 'Enter') {
            @this.set('product_search', event.target.value);
            clearTimeout(window.productSearchTimeout);
        } else {
            clearTimeout(window.productSearchTimeout);
            window.productSearchTimeout = setTimeout(() => {
                @this.set('product_search', event.target.value);
            }, 1000);
        }
    }
</script>
@endpush

@push('scripts')
@livewire('admin.branches.branch-modal')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
