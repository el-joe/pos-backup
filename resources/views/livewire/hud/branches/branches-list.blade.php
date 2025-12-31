<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.branches.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.branches.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.branches.search') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.branches.search') }} ..."
                            wire:model.blur="filters.search">
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.branches.status') }}</label>
                        <select class="form-select select2" name="filters.active">
                            <option value="all">{{ __('general.pages.branches.all') }}</option>
                            <option value="1">{{ __('general.pages.branches.active') }}</option>
                            <option value="0">{{ __('general.pages.branches.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.branches.reset') }}
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
            <h5 class="mb-0">{{ __('general.pages.branches.branches') }}</h5>

            <div class="d-flex align-items-center gap-2">
                @adminCan('branches.export')
                    <!-- Export Button -->
                    <button class="btn btn-outline-success"
                            wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.branches.export') }}
                    </button>
                @endadminCan
                @adminCan('branches.create')
                <!-- Add New -->
                <button class="btn btn-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editBranchModal"
                        wire:click="$dispatch('branch-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.branches.new_branch') }}
                </button>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.branches.id') }}</th>
                            <th>{{ __('general.pages.branches.name') }}</th>
                            <th>{{ __('general.pages.branches.phone') }}</th>
                            <th>{{ __('general.pages.branches.email') }}</th>
                            <th>{{ __('general.pages.branches.address') }}</th>
                            <th>{{ __('general.pages.branches.tax') }}</th>
                            <th>{{ __('general.pages.branches.active') }}</th>
                            <th class="text-nowrap text-end">{{ __('general.pages.branches.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branches as $branch)
                            <tr>
                                <td>{{ $branch->id }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->phone }}</td>
                                <td>{{ $branch->email }}</td>
                                <td>{{ $branch->address }}</td>
                                <td>
                                    @if($branch->tax)
                                        {{ $branch->tax?->name }} ({{ $branch->tax?->rate ?? 0 }}%)
                                    @else
                                        {{ __('general.pages.branches.n_a') }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $branch->active ? 'success' : 'danger' }}">
                                        {{ $branch->active ? __('general.pages.branches.active') : __('general.pages.branches.inactive') }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    @adminCan('branches.update')
                                    <button class="btn btn-sm btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBranchModal"
                                        wire:click="$dispatch('branch-set-current', { id: {{ $branch->id }} })"
                                        title="{{ __('general.pages.branches.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    @endadminCan
                                    @adminCan('branches.delete')
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="deleteAlert({{ $branch->id }})"
                                        title="{{ __('general.pages.branches.delete') }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endadminCan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $branches->links() }}
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

@push('scripts')
    @livewire('admin.branches.branch-modal')
    @include('layouts.hud.partials.select2-script')
@endpush
