<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.admins.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.admins.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.admins.search_label') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.admins.search_placeholder') }}"
                            wire:model.blur="filters.search">
                    </div>

                    {{-- Filter By Branch --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.admins.branch') }}</label>
                        <select class="form-select select2" name="filters.branch_id">
                            <option value="all">{{ __('general.pages.admins.all') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.admins.status') }}</label>
                        <select class="form-select select2" name="filters.active">
                            <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.admins.all') }}</option>
                            <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.admins.active') }}</option>
                            <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.admins.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.admins.reset') }}
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
            <h5 class="mb-0">{{ __('general.pages.admins.admins') }}</h5>
            <div class="d-flex align-items-center gap-2">
                @adminCan('user_management.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.admins.export') }}
                </button>
                @endadminCan
                @adminCan('user_management.create')
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAdminModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.admins.new_admin') }}
                </a>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.admins.id') }}</th>
                            <th>{{ __('general.pages.admins.name') }}</th>
                            <th>{{ __('general.pages.admins.phone') }}</th>
                            <th>{{ __('general.pages.admins.email') }}</th>
                            <th>{{ __('general.pages.admins.type') }}</th>
                            <th>{{ __('general.pages.admins.branch') }}</th>
                            <th>{{ __('general.pages.admins.active') }}</th>
                            <th class="text-center">{{ __('general.pages.admins.actions') }}</th>
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
                                        {{ $admin->active ? __('general.pages.admins.active') : __('general.pages.admins.inactive') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @adminCan('user_management.update')
                                        <button class="btn btn-sm btn-primary me-1"
                                                data-bs-toggle="modal" data-bs-target="#editAdminModal"
                                                wire:click="setCurrent({{ $admin->id }})"
                                                title="{{ __('general.pages.admins.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endadminCan
                                    @adminCan('user_management.delete')
                                        <button class="btn btn-sm btn-danger"
                                                wire:click="deleteAlert({{ $admin->id }})"
                                                title="{{ __('general.pages.admins.delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endadminCan
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
                    <h5 class="modal-title" id="editAdminModalLabel">{{ $current?->id ? __('general.pages.admins.edit') : __('general.pages.admins.new_admin') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="adminName" class="form-label">{{ __('general.pages.admins.name') }}</label>
                            <input type="text" class="form-control" wire:model="data.name" id="adminName" placeholder="Enter admin name">
                        </div>

                        <div class="mb-3">
                            <label for="adminPhone" class="form-label">{{ __('general.pages.admins.phone') }}</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="adminPhone" placeholder="Enter admin phone">
                        </div>

                        <div class="mb-3">
                            <label for="adminEmail" class="form-label">{{ __('general.pages.admins.email') }}</label>
                            <input type="email" class="form-control" wire:model="data.email" id="adminEmail" placeholder="Enter admin email">
                        </div>

                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" wire:model="data.password" id="adminPassword" placeholder="Enter admin password">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adminType" class="form-label">{{ __('general.pages.admins.type') }}</label>
                                <select class="form-select select2" name="data.type" id="adminType">
                                    <option value="">{{ __('general.pages.admins.select_type') }}</option>
                                    <option value="super_admin" {{ ($data['type']??'') == 'super_admin' ? 'selected' : '' }}>{{ __('general.pages.admins.super_admin') }}</option>
                                    <option value="admin" {{ ($data['type']??'') == 'admin' ? 'selected' : '' }}>{{ __('general.pages.admins.admin') }}</option>
                                </select>
                            </div>

                            @if(($data['type']??false) == 'admin')
                                <div class="col-md-6 mb-3">
                                    <label for="adminRole" class="form-label">{{ __('general.pages.admins.role') }}</label>
                                    <select class="form-select select2" name="data.role_id" id="adminRole">
                                        <option value="">{{ __('general.pages.admins.select_role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ ($data['role_id']??'') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="branchId" class="form-label">{{ __('general.pages.admins.branch') }}</label>
                            <select class="form-select select2" name="data.branch_id" id="branchId">
                                <option value="">{{ __('general.pages.purchases.select_branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" wire:model="data.active" id="branchActive">
                            <label class="form-check-label" for="branchActive">
                                {{ __('general.pages.admins.is_active') }}
                            </label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('general.pages.admins.close') }}
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="fa fa-save"></i> {{ __('general.pages.admins.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    @include('layouts.hud.partials.select2-script')
@endpush
