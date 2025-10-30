<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Admins</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit admin --}}
                <a  class="btn btn-primary" data-toggle="modal" data-target="#editAdminModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Admin
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Branch</th>
                        <th>Active</th>
                        <th class="text-nowrap">Action</th>
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
                                <span class="badge badge-{{ $admin->active ? 'success' : 'danger' }}">
                                    {{ $admin->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#editAdminModal" wire:click="setCurrent({{ $admin->id }})" data-toggle="tooltip" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary m-r-10"></i>
                                </a>
                                <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $admin->id }})">
                                    <i class="fa fa-close text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            {{-- <div class="pagination-wrapper t-a-c">
                {{ $admins->links() }}
            </div> --}}
        </div>
    </div>

    {{-- add edit modal for branches page --}}
    <div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="adminName">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="adminName" placeholder="Enter admin name">
                        </div>
                        <div class="form-group">
                            <label for="adminPhone">Phone</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="adminPhone" placeholder="Enter admin phone">
                        </div>
                        <div class="form-group">
                            <label for="adminEmail">Email</label>
                            <input type="email" class="form-control" wire:model="data.email" id="adminEmail" placeholder="Enter admin email">
                        </div>
                        <div class="form-group">
                            <label for="adminPassword">Password</label>
                            <input type="password" class="form-control" wire:model="data.password" id="adminPassword" placeholder="Enter admin password">
                        </div>
                        <div class="form-group">
                            <label for="adminType">Type</label>
                            <select class="form-control" wire:model="data.type" id="adminType">
                                <option value="">Select Type</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        {{-- branches list --}}
                        <div class="form-group">
                            <label for="branchId">Branch</label>
                            <select class="form-control" wire:model="data.branch_id" id="branchId">
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="custom-checkbox">
                                <input type="checkbox" id="branchActive" wire:model="data.active">
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
