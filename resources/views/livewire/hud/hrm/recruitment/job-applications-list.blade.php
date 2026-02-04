<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.hrm.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hrmJobApplicationsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmJobApplicationsFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('hrm.application_number') }} / {{ __('hrm.email') }}" wire:model.blur="search">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.job_opening') }}</label>
                        <select class="form-select select2" name="job_opening_filter">
                            <option value="" {{ $job_opening_filter === '' ? 'selected' : '' }}>{{ __('general.pages.hrm.all') }}</option>
                            @foreach($jobOpenings as $opening)
                                <option value="{{ $opening->id }}" {{ (string)$job_opening_filter === (string)$opening->id ? 'selected' : '' }}>{{ $opening->title ?? $opening->job_title ?? ('#'.$opening->id) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.application_status') }}</label>
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
            <h5 class="mb-0">{{ __('hrm.job_applications') }}</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('hrm.application_number') }}</th>
                            <th>{{ __('hrm.applicant') }}</th>
                            <th>{{ __('hrm.email') }}</th>
                            <th>{{ __('hrm.job_opening') }}</th>
                            <th>{{ __('hrm.application_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>{{ $application->application_number }}</td>
                                <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                                <td>{{ $application->email }}</td>
                                <td>{{ $application->jobOpening?->title ?? $application->jobOpening?->job_title ?? ('#'.$application->job_opening_id) }}</td>
                                <td style="min-width: 220px;">
                                    <select class="form-select" wire:change="updateStatus({{ $application->id }}, $event.target.value)">
                                        @foreach($statuses as $st)
                                            <option value="{{ $st->value }}" {{ (string)($application->status?->value ?? (string)$application->status) === (string)$st->value ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_',' ', $st->value)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">—</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $applications->links() }}
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
