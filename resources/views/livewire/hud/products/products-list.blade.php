<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.products.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.products.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.products.search_sku_name') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.products.search') }} ..."
                            wire:model.blur="filters.search">
                    </div>

                    <div class="col-sm-4">
                        {{-- Branches List --}}
                        <label class="form-label">{{ __('general.pages.products.branch') }}</label>
                        <select class="form-select" wire:model.live="filters.branch_id">
                            <option value="all">{{ __('general.pages.products.all') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-4">
                        {{-- Brands List --}}
                        <label class="form-label">{{ __('general.pages.products.brand') }}</label>
                        <select class="form-select" wire:model.live="filters.brand_id">
                            <option value="all">{{ __('general.pages.products.all') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-4">
                        {{-- Categories List --}}
                        <label class="form-label">{{ __('general.pages.products.category') }}</label>
                        <select class="form-select" wire:model.live="filters.category_id">
                            <option value="all">{{ __('general.pages.products.all') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.products.status') }}</label>
                        <select class="form-select" wire:model.live="filters.active">
                            <option value="all">{{ __('general.pages.products.all') }}</option>
                            <option value="1">{{ __('general.pages.products.active') }}</option>
                            <option value="0">{{ __('general.pages.products.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.products.reset') }}
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
            <h5 class="mb-0"><i class="fa fa-cubes me-2"></i>{{ __('general.pages.products.products') }}</h5>

            <div class="d-flex align-items-center gap-2">
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.products.export') }}
                </button>
                <a href="{{ route('admin.products.add-edit','create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('general.pages.products.new_product') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.products.id') }}</th>
                            <th>{{ __('general.pages.products.sku') }}</th>
                            <th>{{ __('general.pages.products.name') }}</th>
                            <th>{{ __('general.pages.products.branch') }}</th>
                            <th>{{ __('general.pages.products.brand') }}</th>
                            <th>{{ __('general.pages.products.category') }}</th>
                            <th>{{ __('general.pages.products.sell_price') }}</th>
                            <th>{{ __('general.pages.products.branch_stock') }}</th>
                            <th>{{ __('general.pages.products.all_stock') }}</th>
                            <th>{{ __('general.pages.products.status') }}</th>
                            <th class="text-nowrap">{{ __('general.pages.products.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->branch?->name ?? __('general.pages.products.all') }}</td>
                                <td>{{ $product->brand?->name }}</td>
                                <td>{{ $product->category?->name }}</td>
                                <td>{{ $product->stock_sell_price }}</td>
                                <td>{{ $product->branch_stock }}</td>
                                <td>{{ $product->all_stock }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->active ? 'success' : 'danger' }}">
                                        {{ $product->active ? __('general.pages.products.active') : __('general.pages.products.inactive') }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.products.add-edit', $product->id) }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="{{ __('general.pages.products.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger me-1" data-bs-toggle="tooltip" title="{{ __('general.pages.products.delete') }}" wire:click="deleteAlert({{ $product->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="{{ __('general.pages.products.view') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
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

@push('styles')
@endpush
