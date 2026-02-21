<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Attendance Sheet #{{ $sheet->id }}</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.hrm.attendance-sheets.list') }}">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="text-inverse text-opacity-50">Date</div>
                    <div class="fw-bold">{{ optional($sheet->date)->format('Y-m-d') }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-inverse text-opacity-50">Department</div>
                    <div class="fw-bold">{{ $sheet->department?->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-inverse text-opacity-50">Status</div>
                    <div class="fw-bold">{{ $sheet->status }}</div>
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
        <div class="card-header">
            <h6 class="mb-0">Logs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Status</th>
                            <th>Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->employee?->name ?? $l->employee_id }}</td>
                                <td>{{ optional($l->clock_in_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ optional($l->clock_out_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ $l->status ?? '-' }}</td>
                                <td>{{ $l->source ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $logs->links() }}
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
