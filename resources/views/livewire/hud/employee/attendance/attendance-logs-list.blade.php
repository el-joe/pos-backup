<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Attendance</h5>

            @php
                $canCheckIn = !$todayOpenLog;
                $canCheckOut = (bool) $todayOpenLog;
                $todayClockIn = $todayOpenLog?->clock_in_at ?? $todayLog?->clock_in_at;
                $todayClockOut = $todayOpenLog?->clock_out_at ?? $todayLog?->clock_out_at;
            @endphp
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-success" wire:click="checkIn" @disabled(!$canCheckIn)>
                    <i class="fa fa-sign-in-alt me-1"></i> Check In
                </button>
                <button class="btn btn-sm btn-outline-danger" wire:click="checkOut" @disabled(!$canCheckOut)>
                    <i class="fa fa-sign-out-alt me-1"></i> Check Out
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="small text-inverse text-opacity-50">Today</div>
                <div class="d-flex flex-wrap gap-3">
                    <div><span class="text-inverse text-opacity-50">Clock In:</span> <span class="fw-bold">{{ optional($todayClockIn)->format('H:i') ?? '-' }}</span></div>
                    <div><span class="text-inverse text-opacity-50">Clock Out:</span> <span class="fw-bold">{{ optional($todayClockOut)->format('H:i') ?? '-' }}</span></div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
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
                                <td>{{ $l->sheet?->date?->format('Y-m-d') ?? '-' }}</td>
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
