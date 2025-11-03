<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.taxes') }}</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTaxModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus me-1"></i> New Tax
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th class="text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxes as $tax)
                            <tr>
                                <td>{{ $tax->id }}</td>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->rate ?? 0 }}%</td>
                                <td>
                                    <span class="badge bg-{{ $tax->active ? 'success' : 'danger' }}">
                                        {{ $tax->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <button type="button"
                                            class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTaxModal"
                                            wire:click="setCurrent({{ $tax->id }})">
                                        <i class="fa fa-edit me-1"></i> Edit
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            wire:click="deleteAlert({{ $tax->id }})">
                                        <i class="fa fa-trash me-1"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $taxes->links() }}
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
    <div class="modal fade" id="editTaxModal" tabindex="-1" aria-labelledby="editTaxModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Tax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taxName" class="form-label">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="taxName" placeholder="Enter tax name">
                    </div>

                    <div class="mb-3">
                        <label for="taxRate" class="form-label">Rate</label>
                        <input type="number" class="form-control" wire:model="data.rate" id="taxRate" placeholder="Enter tax rate">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="taxActive" wire:model="data.active">
                        <label class="form-check-label" for="taxActive">Is Active</label>
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
