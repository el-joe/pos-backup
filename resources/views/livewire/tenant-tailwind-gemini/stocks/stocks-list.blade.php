<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.titles.stocks')" icon="fa-filter" collapse-id="stocksFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#stocksFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.stocks.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.stocks.search_product') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.stocks.search_placeholder') }}" wire:model.blur="filters.search">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.stocks.branch') }}</label>
                @if(admin()->branch_id)
                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                @else
                    <select class="form-select select2" name="filters.branch_id">
                        <option value="all">{{ __('general.pages.stocks.all') }}</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? 'all') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.stocks.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stocks.stocks_list')" icon="fa-warehouse">
        <x-slot:actions>
            <a href="{{ route('admin.products.list') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-cubes"></i> {{ __('general.pages.products.products') }}
            </a>
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.stocks.id') }}</th>
                <th>{{ __('general.pages.stocks.product') }}</th>
                <th>{{ __('general.pages.stocks.branch') }}</th>
                <th>{{ __('general.pages.stocks.unit') }}</th>
                <th>{{ __('general.pages.stocks.qty') }}</th>
                <th>{{ __('general.pages.stocks.unit_cost') }}</th>
                <th>{{ __('general.pages.stocks.sell_price') }}</th>
                <th class="text-nowrap">{{ __('general.pages.stocks.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse($stocks as $stock)
            <tr>
                <td>{{ $stock->id }}</td>
                <td>
                    <div class="fw-semibold">{{ $stock->product?->name }}</div>
                    <div class="text-muted small">{{ $stock->product?->sku }}</div>
                </td>
                <td>{{ $stock->branch?->name }}</td>
                <td>{{ $stock->unit?->name }}</td>
                <td>{{ round($stock->qty, 3) }}</td>
                <td>{{ number_format($stock->unit_cost ?? 0, 2) }}</td>
                <td>{{ number_format($stock->sell_price ?? 0, 2) }}</td>
                <td class="text-nowrap">
                    @adminCan('products.show')
                    <a href="{{ route('admin.products.details', $stock->product_id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="{{ __('general.pages.products.view') }}">
                        <i class="fa fa-eye"></i>
                    </a>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">{{ __('general.pages.stocks.no_records') }}</td>
            </tr>
        @endforelse

        <x-slot:footer>
            {{ $stocks->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
