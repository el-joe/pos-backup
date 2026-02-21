<div class="modal fade" id="editHrmDepartmentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">{{ $current?->id ? 'Edit Department' : 'New Department' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" wire:model="data.name" placeholder="Department name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Parent Department ID</label>
                    <input type="number" class="form-control" wire:model="data.parent_id" placeholder="Optional">
                </div>
                <div class="mb-3">
                    <label class="form-label">Manager Employee ID</label>
                    <input type="number" class="form-control" wire:model="data.manager_id" placeholder="Optional">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click="save">Save</button>
            </div>
        </div>
    </div>
</div>
