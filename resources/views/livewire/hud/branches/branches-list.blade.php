<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filters</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> Show / Hide
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control"
                            placeholder="Search ..."
                            wire:model.blur="filters.search">
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-select" wire:model.live="filters.active">
                            <option value="all">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.branches') }}</h5>

            <div class="d-flex align-items-center gap-2">
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> Export
                </button>

                <!-- Add New -->
                <button class="btn btn-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editBranchModal"
                        wire:click="setCurrent(null)">
                    <i class="fa fa-plus me-1"></i> New Branch
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Tax</th>
                            <th>Active</th>
                            <th class="text-nowrap text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branches as $branch)
                            <tr>
                                <td>{{ $branch->id }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->phone }}</td>
                                <td>{{ $branch->email }}</td>
                                <td>{{ $branch->address }}</td>
                                <td>
                                    @if($branch->tax)
                                        {{ $branch->tax?->name }} ({{ $branch->tax?->rate ?? 0 }}%)
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $branch->active ? 'success' : 'danger' }}">
                                        {{ $branch->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <button class="btn btn-sm btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBranchModal"
                                        wire:click="setCurrent({{ $branch->id }})"
                                        title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="deleteAlert({{ $branch->id }})"
                                        title="Delete">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $branches->links() }}
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
    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBranchModalLabel">
                        {{ $current?->id ? 'Edit' : 'New' }} Branch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="branchName" class="form-label">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="branchName" placeholder="Enter branch name">
                        </div>

                        <div class="mb-3">
                            <label for="branchPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="branchPhone" placeholder="Enter branch phone">
                        </div>

                        <div class="mb-3">
                            <label for="branchEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" wire:model="data.email" id="branchEmail" placeholder="Enter branch email">
                        </div>

                        <div class="mb-3">
                            <label for="branchAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" wire:model="data.address" id="branchAddress" placeholder="Enter branch address">
                        </div>

                        <div class="mb-3">
                            <label for="branchWebsite" class="form-label">Website</label>
                            <input type="text" class="form-control" wire:model="data.website" id="branchWebsite" placeholder="Enter branch website">
                        </div>

                        <div class="mb-3">
                            <label for="branchTax" class="form-label">Tax</label>
                            <select class="form-select" wire:model="data.tax_id" id="branchTax">
                                <option value="">Select Tax</option>
                                @foreach ($taxes as $tax)
                                    <option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->rate }}%)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="branchActive" wire:model="data.active">
                            <label class="form-check-label" for="branchActive">Is Active</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
