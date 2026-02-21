<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.hrm_payslips') }}</h5>

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
                        <label class="form-label">{{ __('general.pages.hrm.employee') }} {{ __('general.pages.hrm.id') }}</label>
                        <input type="number" class="form-control" wire:model.blur="filters.employee_id">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.titles.hrm_payroll_runs') }} {{ __('general.pages.hrm.id') }}</label>
                        <input type="number" class="form-control" wire:model.blur="filters.payroll_run_id">
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.hrm.id') }}</th>
                            <th>Run</th>
                            <th>{{ __('general.pages.hrm.employee') }}</th>
                            <th>{{ __('general.pages.hrm.gross') }}</th>
                            <th>{{ __('general.pages.hrm.net') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slips as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>{{ $s->run?->id ?? $s->payroll_run_id }}</td>
                                <td>{{ $s->employee?->name ?? $s->employee_id }}</td>
                                <td>{{ numFormat($s->gross_pay) }}</td>
                                <td>{{ numFormat($s->net_pay) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $slips->links() }}
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
