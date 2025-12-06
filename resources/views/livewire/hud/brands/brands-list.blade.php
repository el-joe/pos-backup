<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.brands.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.brands.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.brands.search') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.brands.search') }} ..."
                            wire:model.blur="filters.search">
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.brands.status') }}</label>
                        <select class="form-select" wire:model.live="filters.active">
                            <option value="all">{{ __('general.pages.brands.all') }}</option>
                            <option value="1">{{ __('general.pages.brands.active') }}</option>
                            <option value="0">{{ __('general.pages.brands.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.brands.reset') }}
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
            <h5 class="mb-0">{{ __('general.pages.brands.brands') }}</h5>
            <div class="d-flex align-items-center gap-2">
                @adminCan('brands.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.brands.export') }}
                </button>
                @endadminCan
                @adminCan('brands.create')
                <button class="btn btn-theme" data-bs-toggle="modal" data-bs-target="#editBrandModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.brands.new_brand') }}
                </button>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.brands.name') }}</th>
                            <th>{{ __('general.pages.brands.status') }}</th>
                            <th class="text-nowrap text-end">{{ __('general.pages.brands.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>{{ $brand->id }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $brand->active ? 'success' : 'danger' }}">
                                        {{ $brand->active ? __('general.pages.brands.active') : __('general.pages.brands.inactive') }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    @adminCan('brands.update')
                                    <button class="btn btn-sm btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBrandModal"
                                        wire:click="setCurrent({{ $brand->id }})"
                                        title="{{ __('general.pages.brands.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    @endadminCan
                                    @adminCan('brands.delete')
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="deleteAlert({{ $brand->id }})"
                                        title="{{ __('general.pages.brands.delete') }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endadminCan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $brands->links() }}
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

    <!-- Edit Brand Modal -->
    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBrandModalLabel">{{ $current?->id ? __('general.pages.brands.edit_brand') : __('general.pages.brands.new_brand') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="brandName" class="form-label">{{ __('general.pages.brands.name') }}</label>
                            <input type="text" class="form-control" wire:model="data.name" id="brandName" placeholder="{{ __('general.pages.brands.name') }}">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="brandActive" wire:model="data.active">
                            <label class="form-check-label" for="brandActive">{{ __('general.pages.brands.is_active') }}</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.brands.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.brands.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
@endpush
