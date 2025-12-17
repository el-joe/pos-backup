<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.users.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.users.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.users.search_label') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.users.search_placeholder') }}"
                            wire:model.blur="filters.search">
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.users.status') }}</label>
                        <select class="form-select" wire:model.live="filters.active">
                            <option value="all">{{ __('general.pages.users.all') }}</option>
                            <option value="1">{{ __('general.pages.users.active') }}</option>
                            <option value="0">{{ __('general.pages.users.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.users.reset') }}
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
            <h5 class="mb-0">{{ $type != null ? ucfirst($type) : __('general.pages.users.users') }}</h5>
            <div class="d-flex align-items-center gap-2">
                <?php $_name = $type == 'customer' ? 'customers' : ($type == 'supplier' ? 'suppliers' : 'users'); ?>
                @adminCan($_name . '.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.users.export') }}
                </button>
                @endadminCan
                @adminCan($_name . '.create')
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : null , type: '{{ $type }}' })">
                    <i class="fa fa-plus"></i> {{ __('general.pages.users.new_user') }}
                </a>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.users.id') }}</th>
                            <th>{{ __('general.pages.users.name') }}</th>
                            <th>{{ __('general.pages.users.email') }}</th>
                            <th>{{ __('general.pages.users.phone') }}</th>
                            <th>{{ __('general.pages.users.address') }}</th>
                            @if($type == 'customer')
                            <th>{{ __('general.pages.users.sales_threshold') }}</th>
                            @endif
                            <th>{{ __('general.pages.users.active') }}</th>
                            <th class="text-nowrap">{{ __('general.pages.users.action') }}</th>
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
                                <td>{{ currency()->symbol }}{{ $user->sales_threshold }}</td>
                                @endif
                                <td>
                                    <span class="badge bg-{{ $user->active ? 'success' : 'danger' }}">
                                        {{ $user->active ? __('general.pages.users.active') : __('general.pages.users.inactive') }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    @adminCan($_name . '.update')
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : {{ $user->id }}, type: '{{ $type }}' })" title="{{ __('general.pages.users.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    @endadminCan
                                    @adminCan($_name . '.delete')
                                    <button class="btn btn-sm btn-danger me-1" wire:click="deleteAlert({{ $user->id }})" title="{{ __('general.pages.users.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endadminCan
                                    @adminCan($_name . '.show')
                                    <a href="{{ route('admin.users.details', $user->id) }}" class="btn btn-sm btn-info" title="{{ __('general.pages.users.view') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @endadminCan
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
</div>

@push('styles')
@livewire('admin.users.user-modal',['type'=> $type])
@endpush
