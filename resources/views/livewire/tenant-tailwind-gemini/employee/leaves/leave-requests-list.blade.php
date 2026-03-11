<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card title="New Leave Request" icon="fa-plus-square">
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
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card title="My Leave Requests" icon="fa-calendar-minus-o">
        <x-slot:head>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Start</th>
                <th>End</th>
                <th>Days</th>
                <th>Status</th>
            </tr>
        </x-slot:head>
        @foreach($leaveRequests as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->leaveType?->name ?? $r->leave_type_id }}</td>
                <td>{{ optional($r->start_date)->format('Y-m-d') }}</td>
                <td>{{ optional($r->end_date)->format('Y-m-d') }}</td>
                <td>{{ numFormat($r->days) }}</td>
                <td>{{ $r->status?->label() ?? '-' }}</td>
            </tr>
        @endforeach
        <x-slot:footer>
            {{ $leaveRequests->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
