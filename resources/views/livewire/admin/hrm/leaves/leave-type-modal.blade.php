<div wire:ignore.self class="modal fade" id="editHrmLeaveTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" wire:model="data.name">
                        @error('data.name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Yearly Allowance</label>
                        <input type="number" step="0.01" class="form-control" wire:model="data.yearly_allowance">
                        @error('data.yearly_allowance')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Paid</label>
                        <select class="form-select" wire:model="data.is_paid">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        @error('data.is_paid')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-theme" wire:click="save">Save</button>
            </div>
        </div>
    </div>
</div>
