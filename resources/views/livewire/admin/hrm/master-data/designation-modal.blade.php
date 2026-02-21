<div class="modal fade" id="editHrmDesignationModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">{{ $current?->id ? 'Edit Designation' : 'New Designation' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" wire:model="data.title" placeholder="Designation title">
                </div>
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select class="form-select" wire:model="data.department_id">
                        <option value="">Select</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Base Salary Range</label>
                    <input type="text" class="form-control" wire:model="data.base_salary_range" placeholder="e.g. 1000-2000">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click="save">Save</button>
            </div>
        </div>
    </div>
</div>
