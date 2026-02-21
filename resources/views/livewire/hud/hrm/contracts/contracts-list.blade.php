<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Contracts</h5>

            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="collapse"
                        aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                        wire:click="$toggle('collapseFilters')"
                        data-bs-target="#hrmContractsFilterCollapse">
                    <i class="fa fa-filter me-1"></i> Show/Hide
                </button>

                @adminCan('hrm_master_data.create')
                    <button class="btn btn-sm btn-theme"
                            data-bs-toggle="modal"
                            data-bs-target="#editHrmContractModal"
                            wire:click="$dispatch('hrm-contract-set-employee', null)">
                        <i class="fa fa-plus me-1"></i> New
                    </button>
                @endadminCan
            </div>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmContractsFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
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

            <div class="d-flex justify-content-center mt-3">
                {{ $contracts->links() }}
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
    @livewire('admin.hrm.master-data.contract-modal')
@endpush
