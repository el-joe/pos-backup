<x-admin.table-card title="Roles" icon="fa-shield">
    <x-slot:actions>
        <a class="btn btn-primary btn-sm" href="{{ route('admin.roles.show','create') }}">
            <i class="fa fa-plus"></i> New Role
        </a>
    </x-slot:actions>

    <x-slot:head>
        <tr>
            <th>#</th>
            <th>Role</th>
            <th>Members</th>
            <th>Created At</th>
            <th>Status</th>
            <th class="w-65px">Actions</th>
        </tr>
    </x-slot:head>

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
</x-admin.table-card>
