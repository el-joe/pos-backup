<div class="col-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">{{ __('general.pages.roles.roles') }}</h3>
            @adminCan('role_management.create')
            <a class="btn btn-primary" href="{{ route('admin.roles.show','create') }}">
                <i class="fa fa-plus"></i> {{ __('general.pages.roles.new_role') }}
            </a>
            @endadminCan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('general.pages.roles.id') }}</th>
                            <th>{{ __('general.pages.roles.role') }}</th>
                            <th>{{ __('general.pages.roles.members') }}</th>
                            <th>{{ __('general.pages.roles.created_at') }}</th>
                            <th>{{ __('general.pages.roles.status') }}</th>
                            <th class="text-center" style="width: 80px;">{{ __('general.pages.roles.actions') }}</th>
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
                                    {{ $role->active == 1 ? __('general.pages.roles.active') : __('general.pages.roles.inactive') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @adminCan('role_management.update')
                                <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-primary" title="{{ __('general.pages.admins.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endadminCan
                                @adminCan('role_management.delete')
                                <button type="button" class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $role->id }})" title="{{ __('general.pages.admins.delete') }}">
                                    <i class="fa fa-trash"></i>
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
