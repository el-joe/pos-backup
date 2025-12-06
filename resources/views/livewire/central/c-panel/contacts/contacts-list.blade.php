<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.contacts.contacts') }}</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAdminModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.contacts.new_contact') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.admins.id') }}</th>
                            <th>{{ __('general.pages.admins.name') }}</th>
                            <th>{{ __('general.pages.admins.email') }}</th>
                            <th>{{ __('general.pages.admins.active') }}</th>
                            <th class="text-center">{{ __('general.pages.admins.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $admin->active ? 'success' : 'danger' }}">
                                        {{ $admin->active ? __('general.pages.admins.active') : __('general.pages.admins.inactive') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal" data-bs-target="#editAdminModal"
                                            wire:click="setCurrent({{ $admin->id }})"
                                            title="{{ __('general.pages.admins.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            wire:click="deleteAlert({{ $admin->id }})"
                                            title="{{ __('general.pages.admins.delete') }}">
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
                            <label for="adminEmail" class="form-label">{{ __('general.pages.admins.email') }}</label>
                            <input type="email" class="form-control" wire:model="data.email" id="adminEmail" placeholder="Enter admin email">
                        </div>

                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" wire:model="data.password" id="adminPassword" placeholder="Enter admin password">
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
