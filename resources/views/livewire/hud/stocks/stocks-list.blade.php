<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.stocks') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#stocksFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.stocks.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="stocksFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stocks.search_product') }}</label>
                        <input type="text" class="form-control"
                               placeholder="{{ __('general.pages.stocks.search_placeholder') }}"
                               wire:model.blur="filters.search">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stocks.branch') }}</label>
                        @if(admin()->branch_id)
                            <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                        @else
                            <select class="form-select select2" name="filters.branch_id">
                                <option value="all">{{ __('general.pages.stocks.all') }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? 'all') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
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
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-warehouse me-2"></i>{{ __('general.pages.stocks.stocks_list') }}</h5>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.products.list') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-cubes"></i> {{ __('general.pages.products.products') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
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
                    </thead>
                    <tbody>
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
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $stocks->links() }}
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>

@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
