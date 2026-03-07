<div class="col-12">
    <x-hud.filter-card :title="__('general.pages.products.filters')" icon="fa-filter" collapse-id="branchFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.products.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.products.search_sku_name') }}</label>
                <input type="text" class="form-control"
                    placeholder="{{ __('general.pages.products.search') }} ..."
                    wire:model.blur="filters.search">
            </div>

            <div class="col-sm-4">
                <label class="form-label">{{ __('general.pages.products.branch') }}</label>
                <select class="form-select select2" name="filters.branch_id">
                    <option value="all">{{ __('general.pages.products.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branch->id == ($filters['branch_id']??0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-4">
                <label class="form-label">{{ __('general.pages.products.brand') }}</label>
                <select class="form-select select2" name="filters.brand_id">
                    <option value="all">{{ __('general.pages.products.all') }}</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $brand->id == ($filters['brand_id']??0) ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-4">
                <label class="form-label">{{ __('general.pages.products.category') }}</label>
                <select class="form-select select2" name="filters.category_id">
                    <option value="all">{{ __('general.pages.products.all') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == ($filters['category_id']??0) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.products.status') }}</label>
                <select class="form-select select2" name="filters.active">
                    <option value="all" {{ ($filters['active']??'all') === 'all' ? 'selected' : '' }}>{{ __('general.pages.products.all') }}</option>
                    <option value="1" {{ ($filters['active']??'all') === '1' ? 'selected' : '' }}>{{ __('general.pages.products.active') }}</option>
                    <option value="0" {{ ($filters['active']??'all') === '0' ? 'selected' : '' }}>{{ __('general.pages.products.inactive') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.products.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.products.products')" icon="fa-cubes">
        <x-slot:actions>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.stocks.list') }}" class="btn btn-outline-primary">
                    <i class="fa fa-warehouse me-1"></i> {{ __('general.titles.stocks') }}
                </a>
                @adminCan('products.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.products.export') }}
                </button>
                @endadminCan
                @adminCan('products.create')
                <a href="{{ route('admin.products.add-edit', ['create']) }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('general.pages.products.new_product') }}
                </a>
                @endadminCan
            </div>
        </x-slot:actions>

        <x-slot:head>
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
        </x-slot:head>

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
                    @adminCan('products.update')
                    <a href="{{ route('admin.products.add-edit', $product->id) }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="{{ __('general.pages.products.edit') }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    @endadminCan
                    @adminCan('products.delete')
                    <button type="button" class="btn btn-sm btn-danger me-1" data-bs-toggle="tooltip" title="{{ __('general.pages.products.delete') }}" wire:click="deleteAlert({{ $product->id }})">
                        <i class="fa fa-trash"></i>
                    </button>
                    @endadminCan
                    @adminCan('products.show')
                    <a href="{{ route('admin.products.details', $product->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="{{ __('general.pages.products.view') }}">
                        <i class="fa fa-eye"></i>
                    </a>
                    @endadminCan
                </td>
            </tr>
        @endforeach

        <x-slot:footer>
            {{ $products->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
</div>

@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
