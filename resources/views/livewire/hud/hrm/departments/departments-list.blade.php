<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.hrm.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hrmDepartmentsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmDepartmentsFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('hrm.department') }} ..." wire:model.blur="search">
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}
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

    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('hrm.departments') }}</h5>
            <div class="d-flex gap-2">
                @adminCan('hrm_departments.create')
                <button type="button" class="btn btn-primary" wire:click="openModal()">
                    <i class="fa fa-plus me-1"></i> {{ __('hrm.add_department') }}
                </button>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            @if($showModal)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">
                            {{ $department_id ? __('hrm.edit_department') : __('hrm.add_department') }}
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="closeModal">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('general.name') ?? 'Name' }}</label>
                            <input type="text" class="form-control" wire:model.defer="name">
                            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('general.ar_name') ?? 'Arabic Name' }}</label>
                            <input type="text" class="form-control" wire:model.defer="ar_name">
                            @error('ar_name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('hrm.parent_department') }}</label>
                            <select class="form-select select2" name="parent_id">
                                <option value="">--</option>
                                @foreach($parent_departments as $parent)
                                    <option value="{{ $parent->id }}" {{ (string)$parent_id === (string)$parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('hrm.manager') }}</label>
                            <select class="form-select select2" name="manager_id">
                                <option value="">--</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ (string)$manager_id === (string)$emp->id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                            @error('manager_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('general.description') ?? 'Description' }}</label>
                            <textarea class="form-control" rows="2" wire:model.defer="description"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('general.ar_description') ?? 'Arabic Description' }}</label>
                            <textarea class="form-control" rows="2" wire:model.defer="ar_description"></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="deptActive" wire:model.defer="active">
                                <label class="form-check-label" for="deptActive">{{ __('general.active') ?? __('hrm.active') }}</label>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">{{ __('general.pages.hrm.close') }}</button>
                            <button type="button" class="btn btn-primary" wire:click="save">
                                <i class="fa fa-save me-1"></i> {{ __('general.pages.hrm.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.name') ?? 'Name' }}</th>
                            <th>{{ __('hrm.parent_department') }}</th>
                            <th>{{ __('hrm.manager') }}</th>
                            <th>{{ __('hrm.employees') }}</th>
                            <th>{{ __('general.pages.hrm.status') }}</th>
                            <th class="text-nowrap text-end">{{ __('general.pages.hrm.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td>{{ $department->id }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->parent?->name }}</td>
                                <td>{{ $department->manager?->first_name }} {{ $department->manager?->last_name }}</td>
                                <td>{{ $department->employees?->count() ?? 0 }}</td>
                                <td>
                                    <span class="badge bg-{{ $department->active ? 'success' : 'secondary' }}">
                                        {{ $department->active ? __('hrm.active') : __('hrm.inactive') }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    @adminCan('hrm_departments.update')
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" wire:click="openModal({{ $department->id }})" title="{{ __('general.pages.hrm.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    @endadminCan
                                    @adminCan('hrm_departments.delete')
                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="deleteDepartment({{ $department->id }})" wire:confirm="{{ __('general.pages.hrm.are_you_sure') }}" title="{{ __('general.pages.hrm.delete') }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endadminCan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">—</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $departments->links() }}
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
    @include('layouts.hud.partials.select2-script')
@endpush
