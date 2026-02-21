<div class="modal fade" id="editHrmContractModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">New / Replace Active Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Employee</label>
                    <select class="form-select" wire:model="data.employee_id">
                        <option value="">Select</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Basic Salary</label>
                    <input type="number" step="0.01" class="form-control" wire:model="data.basic_salary" placeholder="0.00">
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" wire:model="data.start_date">
                    </div>
                    <div class="col-6">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" wire:model="data.end_date">
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
