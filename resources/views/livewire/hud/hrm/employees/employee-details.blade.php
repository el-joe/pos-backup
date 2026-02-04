<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">{{ __('hrm.employee_details') }}</h5>
                <div class="text-muted small">{{ $employee->first_name }} {{ $employee->last_name }} @if($employee->employee_code) • {{ $employee->employee_code }} @endif</div>
            </div>
            <div class="d-flex gap-2">
                @adminCan('hrm_employees.update')
                <a class="btn btn-primary" href="{{ route('admin.hrm.employees.edit', $employee->id) }}">
                    <i class="fa fa-edit me-1"></i> {{ __('general.pages.hrm.edit') }}
                </a>
                @endadminCan
                <a class="btn btn-outline-secondary" href="{{ route('admin.hrm.employees.list') }}">
                    <i class="fa fa-arrow-left me-1"></i> {{ __('general.pages.hrm.back') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-pills mb-3">
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}" wire:click="setTab('profile')">
                        {{ __('hrm.personal_information') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'employment' ? 'active' : '' }}" wire:click="setTab('employment')">
                        {{ __('hrm.employment_details') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'attendance' ? 'active' : '' }}" wire:click="setTab('attendance')">
                        {{ __('hrm.attendance') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'leaves' ? 'active' : '' }}" wire:click="setTab('leaves')">
                        {{ __('hrm.leave_requests') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'payslips' ? 'active' : '' }}" wire:click="setTab('payslips')">
                        {{ __('hrm.payslips') }}
                    </button>
                </li>
            </ul>

            @if($activeTab === 'profile')
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-2">{{ __('hrm.personal_information') }}</div>
                            <div class="small text-muted">{{ __('hrm.email') }}: {{ $employee->email }}</div>
                            <div class="small text-muted">{{ __('hrm.phone') }}: {{ $employee->phone }}</div>
                            <div class="small text-muted">{{ __('hrm.mobile') }}: {{ $employee->mobile }}</div>
                            <div class="small text-muted">{{ __('hrm.gender') }}: {{ ucfirst($employee->gender ?? '') }}</div>
                            <div class="small text-muted">{{ __('hrm.date_of_birth') }}: {{ optional($employee->date_of_birth)->format('Y-m-d') }}</div>
                            <div class="small text-muted">{{ __('hrm.address') }}: {{ $employee->address }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-2">{{ __('hrm.documents') }}</div>
                            <div class="text-muted small">{{ __('hrm.national_id') }}: {{ $employee->national_id }}</div>
                            <div class="text-muted small">{{ __('hrm.passport_number') }}: {{ $employee->passport_number }}</div>
                            <div class="text-muted small mt-2">{{ __('hrm.bio') ?? 'Bio' }}:</div>
                            <div class="small">{{ $employee->bio ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeTab === 'employment')
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-2">{{ __('hrm.employment_details') }}</div>
                            <div class="small text-muted">{{ __('hrm.department') }}: {{ $employee->department?->name }}</div>
                            <div class="small text-muted">{{ __('hrm.designation') }}: {{ $employee->designation?->name }}</div>
                            <div class="small text-muted">{{ __('hrm.branch') }}: {{ $employee->branch?->name }}</div>
                            <div class="small text-muted">{{ __('hrm.manager') }}: {{ $employee->manager?->first_name }} {{ $employee->manager?->last_name }}</div>
                            <div class="small text-muted">{{ __('hrm.employment_type') }}: {{ ucfirst(str_replace('_',' ', $employee->employment_type?->value ?? '')) }}</div>
                            <div class="small text-muted">{{ __('hrm.status') }}: {{ ucfirst(str_replace('_',' ', $employee->status?->value ?? '')) }}</div>
                            <div class="small text-muted">{{ __('hrm.joining_date') }}: {{ optional($employee->joining_date)->format('Y-m-d') }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-2">{{ __('hrm.bank_details') }}</div>
                            <div class="small text-muted">{{ __('hrm.bank_name') }}: {{ $employee->bank_name }}</div>
                            <div class="small text-muted">{{ __('hrm.account_number') }}: {{ $employee->account_number }}</div>
                            <div class="small text-muted">{{ __('hrm.account_holder_name') }}: {{ $employee->account_holder_name }}</div>
                            <div class="small text-muted">{{ __('hrm.ifsc_code') }}: {{ $employee->ifsc_code }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeTab === 'attendance')
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.date') ?? 'Date' }}</th>
                                <th>{{ __('hrm.clock_in') }}</th>
                                <th>{{ __('hrm.clock_out') }}</th>
                                <th>{{ __('hrm.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->attendances as $attendance)
                                <tr>
                                    <td>{{ optional($attendance->date)->format('Y-m-d') }}</td>
                                    <td>{{ optional($attendance->clock_in)->format('H:i') }}</td>
                                    <td>{{ optional($attendance->clock_out)->format('H:i') }}</td>
                                    <td>{{ ucfirst(str_replace('_',' ', $attendance->status ?? '')) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">—</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            @if($activeTab === 'leaves')
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('hrm.leave_type') }}</th>
                                <th>{{ __('hrm.start_date') }}</th>
                                <th>{{ __('hrm.end_date') }}</th>
                                <th>{{ __('hrm.total_days') }}</th>
                                <th>{{ __('hrm.leave_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->leaveRequests as $leave)
                                <tr>
                                    <td>{{ $leave->leaveType?->name }}</td>
                                    <td>{{ optional($leave->start_date)->format('Y-m-d') }}</td>
                                    <td>{{ optional($leave->end_date)->format('Y-m-d') }}</td>
                                    <td>{{ $leave->total_days }}</td>
                                    <td>{{ ucfirst(str_replace('_',' ', $leave->status?->value ?? (string)$leave->status)) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">—</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            @if($activeTab === 'payslips')
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('hrm.year_filter') ?? 'Year' }}</th>
                                <th>{{ __('hrm.month_filter') ?? 'Month' }}</th>
                                <th>{{ __('hrm.net_salary') }}</th>
                                <th>{{ __('hrm.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->payslips as $payslip)
                                <tr>
                                    <td>{{ $payslip->year }}</td>
                                    <td>{{ $payslip->month }}</td>
                                    <td>{{ $payslip->net_salary }}</td>
                                    <td>{{ ucfirst(str_replace('_',' ', $payslip->status?->value ?? (string)$payslip->status)) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">—</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
