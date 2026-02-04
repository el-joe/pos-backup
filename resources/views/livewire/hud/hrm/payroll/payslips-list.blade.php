<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.hrm.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hrmPayslipsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmPayslipsFilterCollapse">
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
                    <div class="col-md-2">
                        <label class="form-label">{{ __('hrm.year') }}</label>
                        <input type="number" class="form-control" wire:model.live="year_filter">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('hrm.month') }}</label>
                        <input type="number" class="form-control" wire:model.live="month_filter" min="1" max="12">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                        <select class="form-select select2" name="status_filter">
                            <option value="" {{ $status_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ (string)$status_filter === (string)$status->value ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $status->value)) }}</option>
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
            <h5 class="mb-0">{{ __('hrm.payslips') }}</h5>
            @adminCan('hrm_payroll.generate')
            <button type="button" class="btn btn-outline-primary" wire:click="generatePayslips">
                <i class="fa fa-cog me-1"></i> {{ __('hrm.generate_payslips') }}
            </button>
            @endadminCan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('hrm.employee') }}</th>
                            <th>{{ __('hrm.year') }}</th>
                            <th>{{ __('hrm.month') }}</th>
                            <th>{{ __('hrm.gross_salary') }}</th>
                            <th>{{ __('hrm.net_salary') }}</th>
                            <th>{{ __('general.pages.hrm.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payslips as $payslip)
                            <tr>
                                <td>{{ $payslip->id }}</td>
                                <td>{{ $payslip->employee?->first_name }} {{ $payslip->employee?->last_name }}</td>
                                <td>{{ $payslip->year }}</td>
                                <td>{{ $payslip->month }}</td>
                                <td>{{ $payslip->gross_salary }}</td>
                                <td>{{ $payslip->net_salary }}</td>
                                <td>{{ ucfirst(str_replace('_',' ', $payslip->status?->value ?? (string)$payslip->status)) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">—</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $payslips->links() }}
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
