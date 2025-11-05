<div class="col-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Roles</h3>
            <a class="btn btn-primary" href="{{ route('admin.roles.show','create') }}">
                <i class="fa fa-plus"></i> New Role
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Role</th>
                            <th>Members</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $role->name ?? $role->ar_name }}</td>
                            <td class="text-center">{{ $role->users_count ?? 0 }}</td>
                            <td>{{ $role->created_at->format('D, d M Y - h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ $role->active == 1 ? 'success' : 'danger' }}">
                                    {{ $role->active == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $role->id }})" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
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

@push('styles')
{{-- <style>
    .card {
        border-radius: 16px;
        border: 1.5px solid #e3e6ed;
    }
    .card-header {
        background-color: #1e1e2f;
        color: #fff;
        padding: 16px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .card-title {
        font-size: 20px;
        font-weight: 600;
    }
    .table thead th {
        vertical-align: middle;
    }
    .table tbody td {
        vertical-align: middle;
    }
    .btn-sm i {
        font-size: 14px;
    }
</style> --}}
@endpush
