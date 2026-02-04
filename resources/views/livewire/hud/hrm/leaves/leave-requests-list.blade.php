<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.hrm.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hrmLeaveRequestsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmLeaveRequestsFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('hrm.employee') }}" wire:model.blur="search">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.employee') }}</label>
                        <select class="form-select select2" name="employee_filter">
                            <option value="" {{ $employee_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ (string)$employee_filter === (string)$employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.leave_type') }}</label>
                        <select class="form-select select2" name="leave_type_filter">
                            <option value="" {{ $leave_type_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ (string)$leave_type_filter === (string)$type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.leave_status') }}</label>
                        <select class="form-select select2" name="status_filter">
                            <option value="" {{ $status_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ (string)$status_filter === (string)$status->value ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $status->value)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.start_date') }}</label>
                        <input type="date" class="form-control" wire:model.live="date_from">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.end_date') }}</label>
                        <input type="date" class="form-control" wire:model.live="date_to">
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
            <h5 class="mb-0">{{ __('hrm.leave_requests') }}</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('hrm.employee') }}</th>
                            <th>{{ __('hrm.leave_type') }}</th>
                            <th>{{ __('hrm.start_date') }}</th>
                            <th>{{ __('hrm.end_date') }}</th>
                            <th>{{ __('hrm.total_days') }}</th>
                            <th>{{ __('hrm.leave_status') }}</th>
                            <th class="text-nowrap text-end">{{ __('general.pages.hrm.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $leave)
                            <tr>
                                <td>{{ $leave->id }}</td>
                                <td>{{ $leave->employee?->first_name }} {{ $leave->employee?->last_name }}</td>
                                <td>{{ $leave->leaveType?->name }}</td>
                                <td>{{ optional($leave->start_date)->format('Y-m-d') }}</td>
                                <td>{{ optional($leave->end_date)->format('Y-m-d') }}</td>
                                <td>{{ $leave->total_days }}</td>
                                <td>
                                    @php
                                        $statusVal = $leave->status?->value ?? (string)$leave->status;
                                        $badge = in_array($statusVal, ['approved']) ? 'success' : (in_array($statusVal, ['rejected','cancelled']) ? 'danger' : 'warning');
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_',' ', $statusVal)) }}</span>
                                </td>
                                <td class="text-nowrap text-end">
                                    @if(($leave->status?->value ?? (string)$leave->status) === 'pending')
                                        @adminCan('hrm_leaves.approve')
                                        <button type="button" class="btn btn-sm btn-success" wire:click="approveLeave({{ $leave->id }})">
                                            <i class="fa fa-check me-1"></i> {{ __('hrm.approve_leave') }}
                                        </button>
                                        @endadminCan

                                        @adminCan('hrm_leaves.reject')
                                        <button type="button" class="btn btn-sm btn-danger" wire:click="rejectLeave({{ $leave->id }})">
                                            <i class="fa fa-times me-1"></i> {{ __('hrm.reject_leave') }}
                                        </button>
                                        @endadminCan
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">—</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $leaveRequests->links() }}
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
