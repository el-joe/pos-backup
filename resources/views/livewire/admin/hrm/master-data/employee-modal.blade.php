<div class="modal fade" id="editHrmEmployeeModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">{{ $current?->id ? 'Edit Employee' : 'New Employee' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Code</label>
                        <input class="form-control" wire:model="data.employee_code" placeholder="EMP-0001">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Name</label>
                        <input class="form-control" wire:model="data.name" placeholder="Full name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" wire:model="data.email" placeholder="email@company.com">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Phone</label>
                        <input class="form-control" wire:model="data.phone" placeholder="Phone">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Department</label>
                        <select class="form-select" wire:model="data.department_id">
                            <option value="">Select</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Designation</label>
                        <select class="form-select" wire:model="data.designation_id">
                            <option value="">Select</option>
                            @foreach($designations as $des)
                                <option value="{{ $des->id }}">{{ $des->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Manager</label>
                        <select class="form-select" wire:model="data.manager_id">
                            <option value="">None</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Hire Date</label>
                        <input type="date" class="form-control" wire:model="data.hire_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" wire:model="data.status">
                            <option value="active">active</option>
                            <option value="suspended">suspended</option>
                            <option value="terminated">terminated</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password {{ $current?->id ? '(leave blank to keep)' : '' }}</label>
                        <input type="password" class="form-control" wire:model="data.password" placeholder="******">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click="save">Save</button>
            </div>
        </div>
    </div>
</div>
