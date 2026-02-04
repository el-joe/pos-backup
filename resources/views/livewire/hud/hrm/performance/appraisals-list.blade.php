<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.hrm.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hrmAppraisalsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmAppraisalsFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('hrm.employee') }} / {{ __('hrm.appraisal_number') }}" wire:model.blur="search">
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
                        <label class="form-label">{{ __('hrm.appraisal_period') }}</label>
                        <input type="text" class="form-control" placeholder="2026-Q1" wire:model.blur="period_filter">
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
            <h5 class="mb-0">{{ __('hrm.performance_appraisals') }}</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('hrm.appraisal_number') }}</th>
                            <th>{{ __('hrm.employee') }}</th>
                            <th>{{ __('hrm.appraiser') }}</th>
                            <th>{{ __('hrm.reviewer') }}</th>
                            <th>{{ __('hrm.appraisal_period') }}</th>
                            <th>{{ __('hrm.overall_score') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appraisals as $appraisal)
                            <tr>
                                <td>{{ $appraisal->appraisal_number }}</td>
                                <td>{{ $appraisal->employee?->first_name }} {{ $appraisal->employee?->last_name }}</td>
                                <td>{{ $appraisal->appraiser?->first_name }} {{ $appraisal->appraiser?->last_name }}</td>
                                <td>{{ $appraisal->reviewer?->first_name }} {{ $appraisal->reviewer?->last_name }}</td>
                                <td>{{ $appraisal->appraisal_period }}</td>
                                <td>{{ $appraisal->overall_score ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">—</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $appraisals->links() }}
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
