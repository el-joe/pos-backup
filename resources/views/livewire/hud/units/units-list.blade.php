<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.units.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.units.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.units.search') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.units.search') }} ..."
                            wire:model.blur="filters.search">
                    </div>

                    {{-- Filter By Parent Unit --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.units.parent_unit') }}</label>
                        <select class="form-select" wire:model.live="filters.parent_id">
                            <option value="all">{{ __('general.pages.units.all') }}</option>
                            <option value="0">{{ __('general.pages.units.is_parent') }}</option>
                            @foreach($filterUnits as $parentUnit)
                                <option value="{{ $parentUnit->id }}">{{ $parentUnit->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.units.status') }}</label>
                        <select class="form-select" wire:model.live="filters.active">
                            <option value="all">{{ __('general.pages.units.all') }}</option>
                            <option value="1">{{ __('general.pages.units.active') }}</option>
                            <option value="0">{{ __('general.pages.units.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.units.reset') }}
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
            <h5 class="mb-0">{{ __('general.pages.units.units') }}</h5>
            <div class="d-flex align-items-center gap-2">
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.units.export') }}
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUnitModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.units.new_unit') }}
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.units.name') }}</th>
                            <th>{{ __('general.pages.units.parent') }}</th>
                            <th>{{ __('general.pages.units.count') }}</th>
                            <th>{{ __('general.pages.units.status') }}</th>
                            <th class="text-nowrap">{{ __('general.pages.units.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td>{{ $unit->id }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>{{ $unit->parent ? $unit->parent->name : __('general.pages.units.n_a') }}</td>
                                <td>{{ $unit->count }}</td>
                                <td>
                                    <span class="badge bg-{{ $unit->active ? 'success' : 'danger' }}">
                                        {{ $unit->active ? __('general.pages.units.active') : __('general.pages.units.inactive') }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <button type="button"
                                            class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUnitModal"
                                            wire:click="setCurrent({{ $unit->id }})">
                                        <i class="fa fa-edit me-1"></i> {{ __('general.pages.units.edit') }}
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            wire:click="deleteAlert({{ $unit->id }})">
                                        <i class="fa fa-trash me-1"></i> {{ __('general.pages.units.delete') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $units->links() }}
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
    <div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUnitModalLabel">{{ $current?->id ? __('general.pages.units.edit_unit') : __('general.pages.units.new_unit') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">{{ __('general.pages.units.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="{{ __('general.pages.units.enter_unit_name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">{{ __('general.pages.units.parent_unit') }}</label>
                        <select id="parent_id" wire:model.change="data.parent_id" class="form-select">
                            <option value="0">{{ __('general.pages.units.is_parent') }}</option>
                            @foreach ($parents as $parent)
                                {{ recursiveChildrenForOptions($parent, 'children', 'id', 'name', 0) }}
                            @endforeach
                        </select>
                    </div>

                    @if(($data['parent_id'] ?? 0) != 0)
                        <div class="mb-3">
                            <label for="count" class="form-label">{{ __('general.pages.units.count') }}</label>
                            <input type="number" step="any" wire:model="data.count" id="count" class="form-control">
                        </div>
                    @endif

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="categoryActive" wire:model="data.active">
                        <label class="form-check-label" for="categoryActive">{{ __('general.pages.units.is_active') }}</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.units.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.units.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
@endpush
