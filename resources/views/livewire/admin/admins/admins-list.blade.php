<div class="col-sm-12">
    <x-admin.filter-card :title="__('general.pages.admins.filters')" icon="fa-filter" collapse-id="adminAdminsFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="btn btn-default btn-sm" wire:click="$toggle('collapseFilters')" data-toggle="collapse" data-target="#adminAdminsFilterCollapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.admins.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.admins.search_label') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.admins.search_placeholder') }}" wire:model.live="filters.search">
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.admins.branch') }}</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">{{ __('general.pages.admins.all') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.admins.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.admins.all') }}</option>
                    <option value="1">{{ __('general.pages.admins.active') }}</option>
                    <option value="0">{{ __('general.pages.admins.inactive') }}</option>
                </select>
            </div>
            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.admins.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.pages.admins.admins')" icon="fa-user-secret">
        <x-slot:actions>
            @adminCan('user_management.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.admins.export') }}
                </button>
            @endadminCan
            @adminCan('user_management.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editAdminModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.admins.new_admin') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
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
        </x-slot:head>

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
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#editAdminModal" wire:click="setCurrent({{ $admin->id }})" data-toggle="tooltip" data-original-title="Edit">
                        <i class="fa fa-pencil text-primary m-r-10"></i>
                    </a>
                    <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $admin->id }})">
                        <i class="fa fa-close text-danger"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </x-admin.table-card>

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
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="adminType">Type</label>
                                <select class="form-control" wire:model.live="data.type" id="adminType">
                                    <option value="">Select Type</option>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            @if(($data['type']??false) == 'admin')
                                <div class="form-group col-sm-6">
                                    <label for="adminRole">Role</label>
                                    <select class="form-control" wire:model="data.role_id" id="adminRole">
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
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
