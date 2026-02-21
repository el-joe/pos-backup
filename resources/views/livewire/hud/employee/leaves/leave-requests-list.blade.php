<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <h5 class="mb-0">New Leave Request</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Leave Type</label>
                    <select class="form-select" wire:model="form.leave_type_id">
                        <option value="">Select...</option>
                        @foreach($leaveTypes as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                    @error('form.leave_type_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" wire:model="form.start_date">
                    @error('form.start_date')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" wire:model="form.end_date">
                    @error('form.end_date')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Reason</label>
                    <textarea class="form-control" rows="2" wire:model="form.reason"></textarea>
                    @error('form.reason')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-theme" wire:click="submit">Submit</button>
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
            <h5 class="mb-0">My Leave Requests</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $r)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->leaveType?->name ?? $r->leave_type_id }}</td>
                                <td>{{ optional($r->start_date)->format('Y-m-d') }}</td>
                                <td>{{ optional($r->end_date)->format('Y-m-d') }}</td>
                                <td>{{ numFormat($r->days) }}</td>
                                <td>{{ $r->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $leaveRequests->links() }}
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
