<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.hrm_leave_types') }}</h5>

            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="collapse"
                        aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                        wire:click="$toggle('collapseFilters')"
                        data-bs-target="#hrmLeaveTypesFilterCollapse">
                    <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
                </button>

                @adminCan('hrm_leaves.create')
                    <button class="btn btn-sm btn-theme"
                            data-bs-toggle="modal"
                            data-bs-target="#editHrmLeaveTypeModal"
                            wire:click="$dispatch('hrm-leave-type-set-current', null)">
                        <i class="fa fa-plus me-1"></i> {{ __('general.pages.hrm.new') }}
                    </button>
                @endadminCan
            </div>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmLeaveTypesFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                        <input type="text" class="form-control" placeholder="Name..." wire:model.blur="filters.search">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.hrm.id') }}</th>
                            <th>{{ __('general.pages.hrm.name') }}</th>
                            <th>Yearly Allowance</th>
                            <th>Paid</th>
                            <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($types as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->name }}</td>
                                <td>{{ numFormat($t->yearly_allowance) }}</td>
                                <td>
                                    <span class="badge bg-{{ $t->is_paid ? 'success' : 'secondary' }}">
                                        {{ $t->is_paid ? __('general.pages.hrm.yes') : __('general.pages.hrm.no') }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    @adminCan('hrm_leaves.update')
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editHrmLeaveTypeModal"
                                                wire:click="$dispatch('hrm-leave-type-set-current', { id: {{ $t->id }} })">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endadminCan
                                    @adminCan('hrm_leaves.delete')
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $t->id }})">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endadminCan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $types->links() }}
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
    @livewire('admin.hrm.leaves.leave-type-modal')
@endpush
