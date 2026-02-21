<div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-header mb-0">HRM Master Data</h1>

        <div class="d-flex align-items-center gap-2">
            @adminCan('hrm_master_data.create')
                <button class="btn btn-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmDepartmentModal"
                        wire:click="$dispatch('hrm-department-set-current', null)">
                    <i class="fa fa-plus me-1"></i> New Department
                </button>

                <button class="btn btn-outline-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmDesignationModal"
                        wire:click="$dispatch('hrm-designation-set-current', null)">
                    <i class="fa fa-plus me-1"></i> New Designation
                </button>

                <button class="btn btn-outline-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmEmployeeModal"
                        wire:click="$dispatch('hrm-employee-set-current', null)">
                    <i class="fa fa-plus me-1"></i> New Employee
                </button>

                <button class="btn btn-outline-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmContractModal"
                        wire:click="$dispatch('hrm-contract-set-employee', null)">
                    <i class="fa fa-plus me-1"></i> New Contract
                </button>
            @endadminCan
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filters</h5>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hrmMasterDataFilterCollapse">
                <i class="fa fa-filter me-1"></i> Show/Hide
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmMasterDataFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Departments Search</label>
                        <input type="text" class="form-control" placeholder="Department name..." wire:model.blur="filters.departments_search">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Designations Search</label>
                        <input type="text" class="form-control" placeholder="Designation title..." wire:model.blur="filters.designations_search">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Employees Search</label>
                        <input type="text" class="form-control" placeholder="Employee name..." wire:model.blur="filters.employees_search">
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

    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Departments</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Manager</th>
                                    <th class="text-nowrap text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->name }}</td>
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
                                                <button class="btn btn-sm btn-outline-danger" wire:click="deleteDepartmentAlert({{ $d->id }})">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endadminCan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Designations</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th class="text-nowrap text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($designations as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->title }}</td>
                                        <td>{{ $d->department?->name ?? '-' }}</td>
                                        <td class="text-end text-nowrap">
                                            @adminCan('hrm_master_data.update')
                                                <button class="btn btn-sm btn-outline-primary me-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editHrmDesignationModal"
                                                        wire:click="$dispatch('hrm-designation-set-current', { id: {{ $d->id }} })">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            @endadminCan
                                            @adminCan('hrm_master_data.delete')
                                                <button class="btn btn-sm btn-outline-danger" wire:click="deleteDesignationAlert({{ $d->id }})">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endadminCan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

        <div class="col-12 mb-3">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Employees (Latest 100)</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th class="text-nowrap text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $e)
                                    <tr>
                                        <td>{{ $e->id }}</td>
                                        <td>{{ $e->employee_code }}</td>
                                        <td>{{ $e->name }}</td>
                                        <td>{{ $e->email }}</td>
                                        <td>{{ $e->status }}</td>
                                        <td class="text-end text-nowrap">
                                            @adminCan('hrm_master_data.update')
                                                <button class="btn btn-sm btn-outline-primary me-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editHrmEmployeeModal"
                                                        wire:click="$dispatch('hrm-employee-set-current', { id: {{ $e->id }} })">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            @endadminCan
                                            @adminCan('hrm_master_data.delete')
                                                <button class="btn btn-sm btn-outline-danger" wire:click="deleteEmployeeAlert({{ $e->id }})">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endadminCan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

        <div class="col-12 mb-3">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Contracts (Latest 100)</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Basic Salary</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contracts as $c)
                                    <tr>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ $c->employee?->name ?? $c->employee_id }}</td>
                                        <td>{{ numFormat($c->basic_salary) }}</td>
                                        <td>{{ optional($c->start_date)->format('Y-m-d') }}</td>
                                        <td>{{ optional($c->end_date)->format('Y-m-d') ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $c->is_active ? 'success' : 'secondary' }}">
                                                {{ $c->is_active ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    </div>
</div>

@push('scripts')
    @livewire('admin.hrm.master-data.department-modal')
    @livewire('admin.hrm.master-data.designation-modal')
    @livewire('admin.hrm.master-data.employee-modal')
    @livewire('admin.hrm.master-data.contract-modal')
@endpush
