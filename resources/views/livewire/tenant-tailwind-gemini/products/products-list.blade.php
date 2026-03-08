<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.products.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.products.search_sku_name') }}
                </label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.products.search') }} ..."
                       wire:model.blur="filters.search">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.products.branch') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.branch_id">
                    <option value="all">{{ __('general.pages.products.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branch->id == ($filters['branch_id']??0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.products.brand') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.brand_id">
                    <option value="all">{{ __('general.pages.products.all') }}</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $brand->id == ($filters['brand_id']??0) ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.products.category') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.category_id">
                    <option value="all">{{ __('general.pages.products.all') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == ($filters['category_id']??0) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.products.status') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.active">
                    <option value="all" {{ ($filters['active']??'all') === 'all' ? 'selected' : '' }}>{{ __('general.pages.products.all') }}</option>
                    <option value="1" {{ ($filters['active']??'all') === '1' ? 'selected' : '' }}>{{ __('general.pages.products.active') }}</option>
                    <option value="0" {{ ($filters['active']??'all') === '0' ? 'selected' : '' }}>{{ __('general.pages.products.inactive') }}</option>
                </select>
            </div>

            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-3 xl:col-span-5 xl:justify-end mt-2">
                <button type="button"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 md:w-auto dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.products.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.pages.products.products')"
        icon="fa fa-cubes"
    >
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.stocks.list') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-blue-500 bg-white px-3 py-1.5 text-sm font-medium text-blue-600 transition-colors hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20 dark:focus:ring-offset-slate-900">
                    <i class="fa fa-warehouse"></i> {{ __('general.titles.stocks') }}
                </a>

                @adminCan('products.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900"
                            wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.products.export') }}
                    </button>
                @endadminCan

                @adminCan('products.create')
                    <a href="{{ route('admin.products.add-edit', ['create']) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-plus"></i> {{ __('general.pages.products.new_product') }}
                    </a>
                @endadminCan
            </div>
        </x-slot:actions>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                    <tr>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.id') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.sku') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.name') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.branch') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.brand') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.category') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.sell_price') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.branch_stock') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.all_stock') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.products.status') }}</th>
                        <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.products.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                    @forelse ($products as $product)
                        <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-5 py-4">{{ $product->id }}</td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $product->sku }}</td>
                            <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-5 py-4">{{ $product->branch?->name ?? __('general.pages.products.all') }}</td>
                            <td class="px-5 py-4">{{ $product->brand?->name ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $product->category?->name ?? '-' }}</td>
                            <td class="px-5 py-4 font-semibold text-emerald-600 dark:text-emerald-400">{{ $product->stock_sell_price }}</td>
                            <td class="px-5 py-4">{{ $product->branch_stock }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">{{ $product->all_stock }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $product->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                    {{ $product->active ? __('general.pages.products.active') : __('general.pages.products.inactive') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @adminCan('products.show')
                                        <a href="{{ route('admin.products.details', $product->id) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-sky-600 transition-colors hover:bg-sky-50 focus:outline-none focus:ring-2 focus:ring-sky-500/50 dark:text-sky-400 dark:hover:bg-sky-500/10 dark:focus:ring-sky-400/50"
                                           title="{{ __('general.pages.products.view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endadminCan

                                    @adminCan('products.update')
                                        <a href="{{ route('admin.products.add-edit', $product->id) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:text-blue-400 dark:hover:bg-blue-500/10 dark:focus:ring-blue-400/50"
                                           title="{{ __('general.pages.products.edit') }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endadminCan

                                    @adminCan('products.delete')
                                        <button type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500/50 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:focus:ring-rose-400/50"
                                                wire:click="deleteAlert({{ $product->id }})"
                                                title="{{ __('general.pages.products.delete') }}">
                                            <i class="fa fa-trash text-lg"></i>
                                        </button>
                                    @endadminCan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                {{ __('general.pages.products.no_data_found') ?? 'No products found.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($products, 'hasPages') && $products->hasPages())
            <x-slot:footer>
                {{ $products->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
