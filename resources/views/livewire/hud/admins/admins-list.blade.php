<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Admins</h5>
            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAdminModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus"></i> New Admin
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Branch</th>
                            <th>Active</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->phone }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->type }}</td>
                                <td>{{ $admin->branch?->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $admin->active ? 'success' : 'danger' }}">
                                        {{ $admin->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal" data-bs-target="#editAdminModal"
                                            wire:click="setCurrent({{ $admin->id }})"
                                            title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            wire:click="deleteAlert({{ $admin->id }})"
                                            title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination center aligned (optional) --}}
                {{-- <div class="d-flex justify-content-center mt-3">
                    {{ $admins->links() }}
                </div> --}}
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Edit / Create Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="adminName" class="form-label">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="adminName" placeholder="Enter admin name">
                        </div>

                        <div class="mb-3">
                            <label for="adminPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="adminPhone" placeholder="Enter admin phone">
                        </div>

                        <div class="mb-3">
                            <label for="adminEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" wire:model="data.email" id="adminEmail" placeholder="Enter admin email">
                        </div>

                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" wire:model="data.password" id="adminPassword" placeholder="Enter admin password">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adminType" class="form-label">Type</label>
                                <select class="form-select" wire:model.live="data.type" id="adminType">
                                    <option value="">Select Type</option>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            @if(($data['type']??false) == 'admin')
                                <div class="col-md-6 mb-3">
                                    <label for="adminRole" class="form-label">Role</label>
                                    <select class="form-select" wire:model="data.role_id" id="adminRole">
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="branchId" class="form-label">Branch</label>
                            <select class="form-select" wire:model="data.branch_id" id="branchId">
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" wire:model="data.active" id="branchActive">
                            <label class="form-check-label" for="branchActive">
                                Is Active
                            </label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
