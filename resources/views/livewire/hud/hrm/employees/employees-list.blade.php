<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.hrm.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hrmEmployeesFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmEmployeesFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                        <input type="text" class="form-control"
                               placeholder="{{ __('hrm.employee_code') }}, {{ __('hrm.first_name') }}, {{ __('hrm.last_name') }}"
                               wire:model.blur="search">
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">{{ __('hrm.department') }}</label>
                        <select class="form-select select2" name="department_filter">
                            <option value="" {{ $department_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ (string)$department_filter === (string)$department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">{{ __('hrm.designation') }}</label>
                        <select class="form-select select2" name="designation_filter">
                            <option value="" {{ $designation_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}" {{ (string)$designation_filter === (string)$designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">{{ __('hrm.branch') }}</label>
                        <select class="form-select select2" name="branch_filter">
                            <option value="" {{ $branch_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (string)$branch_filter === (string)$branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                        <select class="form-select select2" name="status_filter">
                            <option value="" {{ $status_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ (string)$status_filter === (string)$status->value ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $status->value)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">{{ __('hrm.employment_type') }}</label>
                        <select class="form-select select2" name="employment_type_filter">
                            <option value="" {{ $employment_type_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($employment_types as $type)
                                <option value="{{ $type->value }}" {{ (string)$employment_type_filter === (string)$type->value ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $type->value)) }}</option>
                            @endforeach
                        </select>
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

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('hrm.employees') }}</h5>

            <div class="d-flex align-items-center gap-2">
                @adminCan('hrm_employees.create')
                <a class="btn btn-theme" href="{{ route('admin.hrm.employees.create') }}">
                    <i class="fa fa-plus me-1"></i> {{ __('hrm.add_employee') }}
                </a>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('hrm.employee_code') }}</th>
                            <th>{{ __('hrm.full_name') }}</th>
                            <th>{{ __('hrm.department') }}</th>
                            <th>{{ __('hrm.designation') }}</th>
                            <th>{{ __('hrm.branch') }}</th>
                            <th>{{ __('general.pages.hrm.status') }}</th>
                            <th>{{ __('hrm.employment_type') }}</th>
                            <th class="text-nowrap text-end">{{ __('general.pages.hrm.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->employee_code }}</td>
                                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td>{{ $employee->department?->name }}</td>
                                <td>{{ $employee->designation?->name }}</td>
                                <td>{{ $employee->branch?->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $employee->status?->value === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst(str_replace('_',' ', $employee->status?->value ?? '')) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst(str_replace('_',' ', $employee->employment_type?->value ?? '')) }}</td>
                                <td class="text-end text-nowrap">
                                    @adminCan('hrm_employees.show')
                                    <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('admin.hrm.employees.details', $employee->id) }}" title="{{ __('hrm.employee_details') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @endadminCan

                                    @adminCan('hrm_employees.update')
                                    <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('admin.hrm.employees.edit', $employee->id) }}" title="{{ __('general.pages.hrm.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    @endadminCan

                                    @adminCan('hrm_employees.delete')
                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="deleteEmployee({{ $employee->id }})" wire:confirm="{{ __('general.pages.hrm.are_you_sure') }}" title="{{ __('general.pages.hrm.delete') }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endadminCan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">{{ __('hrm.no_employee_record_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $employees->links() }}
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
