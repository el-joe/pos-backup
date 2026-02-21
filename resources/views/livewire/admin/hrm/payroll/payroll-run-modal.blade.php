<div wire:ignore.self class="modal fade" id="editHrmPayrollRunModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payroll Run</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Month</label>
                        <input type="number" class="form-control" min="1" max="12" wire:model="data.month">
                        @error('data.month')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Year</label>
                        <input type="number" class="form-control" min="2000" max="2100" wire:model="data.year">
                        @error('data.year')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control" wire:model="data.status">
                        @error('data.status')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total Payout</label>
                        <input type="number" step="0.01" class="form-control" wire:model="data.total_payout">
                        @error('data.total_payout')<small class="text-danger">{{ $message }}</small>@enderror
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
