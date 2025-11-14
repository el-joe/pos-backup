<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payment Methods</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPaymentMethodModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus me-1"></i> New Payment Method
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentMethods as $paymentMethod)
                        <tr>
                            <td>{{ $paymentMethod->id }}</td>
                            <td>{{ $paymentMethod->name }}</td>
                            <td>{{ $paymentMethod->branch?->name ?? 'All Branches' }}</td>
                            <td>
                                <span class="badge bg-{{ $paymentMethod->active ? 'success' : 'danger' }}">
                                    {{ $paymentMethod->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if(!in_array($paymentMethod->slug, ['cash', 'bank-transfer']))
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                    data-bs-target="#editPaymentMethodModal"
                                    wire:click="setCurrent({{ $paymentMethod->id }})" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $paymentMethod->id }})" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @else
                                <i class="fa fa-lock text-muted" title="This payment method cannot be edited or deleted"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $paymentMethods->links() }}
                </div>
            </div>
        </div>

        <!-- Card Arrow -->
        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editPaymentMethodModal" tabindex="-1" aria-labelledby="editPaymentMethodModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $current?->id ? 'Edit' : 'New' }} Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    <div class="col-md-6 mb-3">
                        <label for="expenseCategoryName" class="form-label">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="expenseCategoryName" placeholder="Enter payment method name">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="branch_id" class="form-label">Branch</label>
                        @if(admin()->branch_id == null)
                        <select class="form-select" id="branch_id" wire:model="data.branch_id">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                        @endif
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="expenseCategoryActive" wire:model="data.active">
                            <label class="form-check-label" for="expenseCategoryActive">Is Active</label>
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
</div>
