<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.units') }}</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUnitModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus me-1"></i> New Unit
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Count</th>
                            <th>Status</th>
                            <th class="text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td>{{ $unit->id }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>{{ $unit->parent ? $unit->parent->name : 'N/A' }}</td>
                                <td>{{ $unit->count }}</td>
                                <td>
                                    <span class="badge bg-{{ $unit->active ? 'success' : 'danger' }}">
                                        {{ $unit->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <button type="button"
                                            class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUnitModal"
                                            wire:click="setCurrent({{ $unit->id }})">
                                        <i class="fa fa-edit me-1"></i> Edit
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            wire:click="deleteAlert({{ $unit->id }})">
                                        <i class="fa fa-trash me-1"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $units->links() }}
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

    <!-- Modal -->
    <div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUnitModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="Enter unit name">
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Unit</label>
                        <select id="parent_id" wire:model.change="data.parent_id" class="form-select">
                            <option value="0">Is Parent</option>
                            @foreach ($parents as $parent)
                                {{ recursiveChildrenForOptions($parent, 'children', 'id', 'name', 0) }}
                            @endforeach
                        </select>
                    </div>

                    @if(($data['parent_id'] ?? 0) != 0)
                        <div class="mb-3">
                            <label for="count" class="form-label">Count</label>
                            <input type="number" step="any" wire:model="data.count" id="count" class="form-control">
                        </div>
                    @endif

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="categoryActive" wire:model="data.active">
                        <label class="form-check-label" for="categoryActive">Is Active</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
@endpush
