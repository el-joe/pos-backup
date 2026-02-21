<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.taxes.filters') }}</h5>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.taxes.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.taxes.search') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.taxes.search_placeholder') }}"
                            wire:model.blur="filters.search">
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.taxes.status') }}</label>
                        <select class="form-select select2" name="filters.active">
                            <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.taxes.all') }}</option>
                            <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.taxes.active') }}</option>
                            <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.taxes.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.taxes.reset') }}
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
            <h5 class="mb-0">{{ __('general.titles.taxes') }}</h5>
            <div class="d-flex align-items-center gap-2">
                @adminCan('taxes.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.taxes.export') }}
                </button>
                @endadminCan
                @adminCan('taxes.create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTaxModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.taxes.new_tax') }}
                </button>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.taxes.id') }}</th>
                            <th>{{ __('general.pages.taxes.name') }}</th>
                            <th>{{ __('general.pages.taxes.tax_registeration_number') }}</th>
                            <th>{{ __('general.pages.taxes.percentage') }}</th>
                            <th>{{ __('general.pages.taxes.status') }}</th>
                            <th class="text-nowrap">{{ __('general.pages.taxes.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxes as $tax)
                            <tr>
                                <td>{{ $tax->id }}</td>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->vat_number }}</td>
                                <td>{{ $tax->rate ?? 0 }}%</td>
                                <td>
                                    <span class="badge bg-{{ $tax->active ? 'success' : 'danger' }}">
                                        {{ $tax->active ? __('general.pages.taxes.active') : __('general.pages.taxes.inactive') }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    @adminCan('taxes.update')
                                    <button type="button"
                                            class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTaxModal"
                                            wire:click="setCurrent({{ $tax->id }})">
                                        <i class="fa fa-edit me-1"></i> {{ __('general.pages.taxes.edit') }}
                                    </button>
                                    @endadminCan
                                    @adminCan('taxes.delete')
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            wire:click="deleteAlert({{ $tax->id }})">
                                        <i class="fa fa-trash me-1"></i> {{ __('general.pages.taxes.delete') }}
                                    </button>
                                    @endadminCan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $taxes->links() }}
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

    <!-- Modal -->
    <div class="modal fade" id="editTaxModal" tabindex="-1" aria-labelledby="editTaxModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxModalLabel">{{ $current?->id ? __('general.pages.taxes.edit_tax') : __('general.pages.taxes.new_tax') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taxName" class="form-label">{{ __('general.pages.taxes.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="taxName" placeholder="{{ __('general.pages.taxes.enter_tax_name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="taxVatNumber" class="form-label">{{ __('general.pages.taxes.tax_registeration_number') }}</label>
                        <input type="text" class="form-control" wire:model="data.vat_number" id="taxVatNumber" placeholder="{{ __('general.pages.taxes.enter_tax_registration_number') }}">

                    <div class="mb-3">
                        <label for="taxRate" class="form-label">{{ __('general.pages.taxes.rate') }}</label>
                        <input type="number" class="form-control" wire:model="data.rate" id="taxRate" placeholder="{{ __('general.pages.taxes.enter_tax_rate') }}">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="taxActive" wire:model="data.active">
                        <label class="form-check-label" for="taxActive">{{ __('general.pages.taxes.is_active') }}</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.taxes.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.taxes.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('layouts.hud.partials.select2-script')
@endpush
