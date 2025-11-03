<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">{{ $type != null ? ucfirst($type) : 'Users' }}</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit user --}}
                <a  class="btn btn-primary" data-toggle="modal" data-target="#editUserModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New User
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
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
                                <span class="badge badge-{{ $user->active ? 'success' : 'danger' }}">
                                    {{ $user->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#editUserModal" wire:click="setCurrent({{ $user->id }})" data-toggle="tooltip" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary m-r-10"></i>
                                </a>
                                <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $user->id }})">
                                    <i class="fa fa-close text-danger  m-r-10"></i>
                                </a>
                                <a href="{{ route('admin.users.details', $user->id) }}" data-toggle="tooltip" data-original-title="View">
                                    <i class="fa fa-eye text-info"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- add edit modal for users page --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">{{ $current?->id ? 'Edit' : 'New' }} User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="userName">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="userName" placeholder="Enter user name" required>
                        </div>
                        <div class="form-group">
                            <label for="userEmail">Email</label>
                            <input type="email" class="form-control" wire:model="data.email" id="userEmail" placeholder="Enter user email" required>
                        </div>
                        <div class="form-group">
                            <label for="userPhone">Phone</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="userPhone" placeholder="Enter user phone" required>
                        </div>
                        <div class="form-group">
                            <label for="userAddress">Address</label>
                            <input type="text" class="form-control" wire:model="data.address" id="userAddress" placeholder="Enter user address" required>
                        </div>
                        @if($type == 'customer')
                        <div class="form-group">
                            <label for="userSalesThreshold">Sales Threshold</label>
                            <input type="number" class="form-control" wire:model="data.sales_threshold" id="userSalesThreshold" placeholder="Enter sales threshold" min="0" step="0.01" required>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="custom-checkbox">
                                <input type="checkbox" id="userActive" wire:model="data.active">
                                <span class="checkmark"></span> Is Active
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
@endpush
