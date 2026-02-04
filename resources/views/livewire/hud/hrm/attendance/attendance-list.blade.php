<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.hrm.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hrmAttendanceFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmAttendanceFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.from') }}</label>
                        <input type="date" class="form-control" wire:model.live="date_from">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.to') }}</label>
                        <input type="date" class="form-control" wire:model.live="date_to">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.employee') }}</label>
                        <select class="form-select select2" name="employee_filter">
                            <option value="" {{ $employee_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ (string)$employee_filter === (string)$employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.branch') }}</label>
                        <select class="form-select select2" name="branch_filter">
                            <option value="" {{ $branch_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (string)$branch_filter === (string)$branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                        <select class="form-select select2" name="status_filter">
                            <option value="" {{ $status_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ (string)$status_filter === (string)$status->value ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $status->value)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('hrm.first_name') }} / {{ __('hrm.last_name') }}" wire:model.blur="search">
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
            <h5 class="mb-0">{{ __('hrm.attendance_list') }}</h5>
            <a class="btn btn-outline-primary" href="{{ route('admin.hrm.clock-in-out') }}">
                <i class="fa fa-clock me-1"></i> {{ __('hrm.clock_in') }} / {{ __('hrm.clock_out') }}
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.hrm.date') }}</th>
                            <th>{{ __('hrm.employee') }}</th>
                            <th>{{ __('hrm.branch') }}</th>
                            <th>{{ __('hrm.clock_in') }}</th>
                            <th>{{ __('hrm.clock_out') }}</th>
                            <th>{{ __('hrm.working_hours') }}</th>
                            <th>{{ __('general.pages.hrm.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ optional($attendance->date)->format('Y-m-d') }}</td>
                                <td>{{ $attendance->employee?->first_name }} {{ $attendance->employee?->last_name }}</td>
                                <td>{{ $attendance->branch?->name }}</td>
                                <td>{{ optional($attendance->clock_in)->format('H:i') }}</td>
                                <td>{{ optional($attendance->clock_out)->format('H:i') }}</td>
                                <td>{{ $attendance->working_hours }}</td>
                                <td>{{ ucfirst(str_replace('_',' ', $attendance->status ?? '')) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">—</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $attendances->links() }}
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
