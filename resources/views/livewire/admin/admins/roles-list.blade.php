<div class="white-box">
    <div class="row mb-3" style="margin-bottom:15px;">
        <div class="col-xs-6">
            <h3 class="box-title m-b-0" style="margin:0;">Roles</h3>
        </div>
        <div class="col-xs-6 text-right">
            <a class="btn btn-primary" href="{{ route('admin.roles.show','create') }}">
                <i class="fa fa-plus"></i> New Role
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role</th>
                    <th>Members</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th class="w-65px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name ?? $role->ar_name }}</td>
                    <td class="m-auto">{{ $role->users_count ?? 0 }}</td>
                    <td>{{ $role->created_at->format('D, d M Y - h:i A') }}</td>
                    <td>
                        <span class="badge badge-{{ $role->active == 1 ? 'success' : 'danger' }}">
                            {{ $role->active == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.roles.show', $role->id) }}" data-toggle="tooltip" data-original-title="Edit">
                            <i class="fa fa-pencil text-primary m-r-10"></i>
                        </a>
                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Delete" wire:click="deleteAlert({{ $role->id }})">
                            <i class="fa fa-close text-danger"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
