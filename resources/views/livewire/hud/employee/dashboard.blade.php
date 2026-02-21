<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Welcome, {{ $employee->name }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-inverse text-opacity-50">Department</div>
                            <div class="fw-bold">{{ $employee->department?->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-inverse text-opacity-50">Designation</div>
                            <div class="fw-bold">{{ $employee->designation?->title ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-inverse text-opacity-50">Status</div>
                            <div class="fw-bold">{{ $employee->status ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-inverse text-opacity-50">Leave Requests</div>
                            <div class="fw-bold">{{ $leaveRequestsCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-inverse text-opacity-50">Expense Claims</div>
                            <div class="fw-bold">{{ $expenseClaimsCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-inverse text-opacity-50">Payslips</div>
                            <div class="fw-bold">{{ $payslipsCount }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="mb-0">Latest Attendance</h6>
                        </div>
                        <div class="card-body">
                            @if($latestAttendanceLog)
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="text-inverse text-opacity-50">Date</div>
                                        <div class="fw-bold">{{ $latestAttendanceLog->sheet?->date?->format('Y-m-d') ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-inverse text-opacity-50">Clock In</div>
                                        <div class="fw-bold">{{ optional($latestAttendanceLog->clock_in_at)->format('Y-m-d H:i') ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-inverse text-opacity-50">Clock Out</div>
                                        <div class="fw-bold">{{ optional($latestAttendanceLog->clock_out_at)->format('Y-m-d H:i') ?? '-' }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="text-inverse text-opacity-50">No attendance records yet.</div>
                            @endif
                        </div>
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
</div>
