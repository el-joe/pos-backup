<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Attendance Sheets</h5>

            @adminCan('hrm_attendance.create')
                <button class="btn btn-sm btn-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmAttendanceSheetModal"
                        wire:click="$dispatch('hrm-attendance-sheet-set-current', null)">
                    <i class="fa fa-plus me-1"></i> New
                </button>
            @endadminCan
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
                            <th>ID</th>
                            <th>Date</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
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

