<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.hrm.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.stocks.search_product') }}</label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" placeholder="{{ __('general.pages.stocks.search_placeholder') }}" wire:model.blur="filters.search">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.stocks.branch') }}</label>
                @if(admin()->branch_id)
                    <input type="text" class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200" value="{{ admin()->branch?->name }}" disabled>
                @else
                    <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.branch_id">
                        <option value="all">{{ __('general.pages.stocks.all') }}</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? 'all') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="flex items-end justify-start md:col-span-2 lg:col-span-1 lg:justify-end">
                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.stocks.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stocks.stocks_list')" icon="fa fa-warehouse">
        <x-slot:actions>
            <a href="{{ route('admin.products.list') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                <i class="fa fa-cubes"></i> {{ __('general.pages.products.products') }}
            </a>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.stocks.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.stocks.product') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.stocks.branch') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.stocks.unit') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.stocks.qty') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.stocks.unit_cost') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.stocks.sell_price') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.stocks.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse($stocks as $stock)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $stock->id }}</td>
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $stock->product?->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $stock->product?->sku }}</div>
                        </td>
                        <td class="px-5 py-4">{{ $stock->branch?->name }}</td>
                        <td class="px-5 py-4">{{ $stock->unit?->name }}</td>
                        <td class="px-5 py-4">{{ round($stock->qty, 3) }}</td>
                        <td class="px-5 py-4">{{ number_format($stock->unit_cost ?? 0, 2) }}</td>
                        <td class="px-5 py-4">{{ number_format($stock->sell_price ?? 0, 2) }}</td>
                        <td class="px-5 py-4 text-right">
                            @adminCan('products.show')
                                <a href="{{ route('admin.products.details', $stock->product_id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20" title="{{ __('general.pages.products.view') }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endadminCan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.stocks.no_records') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($stocks->hasPages())
            <x-slot:footer>
                {{ $stocks->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
