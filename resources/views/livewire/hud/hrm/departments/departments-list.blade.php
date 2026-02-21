<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Departments</h5>

            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="collapse"
                        aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                        wire:click="$toggle('collapseFilters')"
                        data-bs-target="#hrmDepartmentsFilterCollapse">
                    <i class="fa fa-filter me-1"></i> Show/Hide
                </button>

                @adminCan('hrm_master_data.create')
                    <button class="btn btn-sm btn-theme"
                            data-bs-toggle="modal"
                            data-bs-target="#editHrmDepartmentModal"
                            wire:click="$dispatch('hrm-department-set-current', null)">
                        <i class="fa fa-plus me-1"></i> New
                    </button>
                @endadminCan
            </div>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmDepartmentsFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" placeholder="Department name..." wire:model.blur="filters.search">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> Reset
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Manager</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->parent_id ?? '-' }}</td>
                                <td>{{ $d->manager_id ?? '-' }}</td>
                                <td class="text-end text-nowrap">
                                    @adminCan('hrm_master_data.update')
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editHrmDepartmentModal"
                                                wire:click="$dispatch('hrm-department-set-current', { id: {{ $d->id }} })">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endadminCan
                                    @adminCan('hrm_master_data.delete')
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $d->id }})">
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
                {{ $departments->links() }}
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
    @livewire('admin.hrm.master-data.department-modal')
@endpush
