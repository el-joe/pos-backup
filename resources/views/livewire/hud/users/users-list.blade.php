<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $type != null ? ucfirst($type) : 'Users' }}</h5>
            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus"></i> New User
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            @if($type == 'customer')
                            <th>Sales Threshold</th>
                            @endif
                            <th>Active</th>
                            <th class="text-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->address }}</td>
                                @if($type == 'customer')
                                <td>{{ $user->sales_threshold }}</td>
                                @endif
                                <td>
                                    <span class="badge bg-{{ $user->active ? 'success' : 'danger' }}">
                                        {{ $user->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="setCurrent({{ $user->id }})" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger me-1" wire:click="deleteAlert({{ $user->id }})" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <a href="{{ route('admin.users.details', $user->id) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
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

    <!-- Edit/Add User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">{{ $current?->id ? 'Edit' : 'New' }} User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="userName" class="form-label">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="userName" placeholder="Enter user name" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" wire:model="data.email" id="userEmail" placeholder="Enter user email" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="userPhone" placeholder="Enter user phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="userAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" wire:model="data.address" id="userAddress" placeholder="Enter user address" required>
                        </div>
                        @if($type == 'customer')
                        <div class="mb-3">
                            <label for="userSalesThreshold" class="form-label">Sales Threshold</label>
                            <input type="number" class="form-control" wire:model="data.sales_threshold" id="userSalesThreshold" placeholder="Enter sales threshold" min="0" step="0.01" required>
                        </div>
                        @endif
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="userActive" wire:model="data.active">
                            <label class="form-check-label" for="userActive">Is Active</label>
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

@push('styles')
@endpush
