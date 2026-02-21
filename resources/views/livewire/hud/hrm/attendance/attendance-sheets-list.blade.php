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
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                        <input type="text" class="form-control" wire:model.blur="filters.status">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.department') }}</label>
                        <input type="text" class="form-control" wire:model.blur="filters.department_id">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-secondary w-100" wire:click="$set('filters', [])">{{ __('general.pages.hrm.reset') }}</button>
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
            <h5 class="mb-0">{{ __('general.titles.hrm_attendance_sheets') }}</h5>

            @adminCan('hrm_attendance.create')
                <button class="btn btn-sm btn-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmAttendanceSheetModal"
                        wire:click="$dispatch('hrm-attendance-sheet-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.hrm.id') }}</th>
                            <th>{{ __('general.pages.hrm.date') }}</th>
                            <th>{{ __('general.pages.hrm.department') }}</th>
                            <th>{{ __('general.pages.hrm.status') }}</th>
                            <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sheets as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>{{ optional($s->date)->format('Y-m-d') }}</td>
                                <td>{{ $s->department?->name ?? '-' }}</td>
                                <td>{{ $s->status }}</td>
                                <td class="text-end text-nowrap">
                                    <a class="btn btn-sm btn-outline-theme me-1" href="{{ route('admin.hrm.attendance-sheets.details', $s->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    @adminCan('hrm_attendance.update')
                                        @if(($s->status ?? null) === 'draft')
                                            <button class="btn btn-sm btn-outline-success me-1" wire:click="submitAlert({{ $s->id }})" title="Submit">
                                                <i class="fa fa-paper-plane"></i>
                                            </button>
                                        @endif
                                    @endadminCan

                                    @adminCan('hrm_attendance.approve')
                                        @if(($s->status ?? null) === 'submitted')
                                            <button class="btn btn-sm btn-outline-success me-1" wire:click="approveAlert({{ $s->id }})" title="Approve">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @endif
                                    @endadminCan

                                    @adminCan('hrm_attendance.update')
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editHrmAttendanceSheetModal"
                                                wire:click="$dispatch('hrm-attendance-sheet-set-current', { id: {{ $s->id }} })">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endadminCan
                                    @adminCan('hrm_attendance.delete')
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $s->id }})">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endadminCan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $sheets->links() }}
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
    @livewire('admin.hrm.attendance.attendance-sheet-modal')
@endpush

