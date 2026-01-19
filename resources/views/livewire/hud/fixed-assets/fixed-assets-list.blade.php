<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.fixed_assets.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#fixedAssetFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.fixed_assets.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="fixedAssetFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.search') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('general.pages.fixed_assets.search_placeholder') }}" wire:model.blur="filters.search">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.branch') }}</label>
                        <select class="form-select select2" name="filters.branch_id">
                            <option value="">{{ __('general.layout.all_branches') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.status') }}</label>
                        <select class="form-select select2" name="filters.status">
                            <option value="">{{ __('general.pages.fixed_assets.all_statuses') }}</option>
                            <option value="active" {{ ($filters['status']??'') == 'active' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_active') }}</option>
                            <option value="disposed" {{ ($filters['status']??'') == 'disposed' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_disposed') }}</option>
                            <option value="sold" {{ ($filters['status']??'') == 'sold' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_sold') }}</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.fixed_assets.reset') }}
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
            <h3 class="card-title mb-0">{{ __('general.pages.fixed_assets.fixed_assets') }}</h3>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.fixed_assets.export') }}
                </button>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.fixed-assets.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.fixed_assets.new_fixed_asset') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.fixed_assets.code') }}</th>
                            <th>{{ __('general.pages.fixed_assets.name') }}</th>
                            <th>{{ __('general.pages.fixed_assets.branch') }}</th>
                            <th>{{ __('general.pages.fixed_assets.status') }}</th>
                            <th>{{ __('general.pages.fixed_assets.cost') }}</th>
                            <th>{{ __('general.pages.fixed_assets.net_book_value') }}</th>
                            <th class="text-nowrap text-center">{{ __('general.pages.fixed_assets.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assets as $asset)
                            <tr>
                                <td>{{ $asset->id }}</td>
                                <td>{{ $asset->code }}</td>
                                <td>{{ $asset->name }}</td>
                                <td>{{ $asset->branch?->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $asset->status === 'active' ? 'success' : ($asset->status === 'sold' ? 'info' : 'secondary') }}">
                                        {{ ucfirst($asset->status) }}
                                    </span>
                                </td>
                                <td>{{ currencyFormat($asset->cost ?? 0, true) }}</td>
                                <td>{{ currencyFormat($asset->net_book_value ?? 0, true) }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.fixed-assets.details', $asset->id) }}">
                                        {{ __('general.pages.fixed_assets.details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('general.pages.fixed_assets.no_records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $assets->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
