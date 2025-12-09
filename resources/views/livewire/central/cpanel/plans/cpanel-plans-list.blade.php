<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Plans List</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPlanModal" wire:click="setCurrent(null)">
                    <i class="fa fa-layer-group"></i> New Plan
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price (Month)</th>
                            <th>Price (Year)</th>
                            <th>Active</th>
                            <th>Recommended</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->price_month }}</td>
                            <td>{{ $plan->price_year }}</td>

                            <td>
                                <span class="badge bg-{{ $plan->active ? 'success' : 'danger' }}">
                                    {{ $plan->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-{{ $plan->recommended ? 'primary' : 'secondary' }}">
                                    {{ $plan->recommended ? 'Yes' : 'No' }}
                                </span>
                            </td>

                            <td class="text-center">
                                <!-- trigger active -->
                                @if ($plan->active)
                                <button class="btn btn-sm btn-danger" wire:click="triggerActive({{ $plan->id }})" title="Deactivate">
                                    <i class="fa fa-toggle-off"></i>
                                </button>
                                @else
                                <button class="btn btn-sm btn-success" wire:click="triggerActive({{ $plan->id }})" title="Activate">
                                    <i class="fa fa-toggle-on"></i>
                                </button>
                                @endif

                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editPlanModal" wire:click="setCurrent({{ $plan->id }})" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $plan->id }})" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination --}}
                {{-- <div class="d-flex justify-content-center mt-3">
                    {{ $plans->links() }}
            </div> --}}
        </div>
    </div>

    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
</div>

<!-- Edit/Create Plan Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlanModalLabel">
                    {{ $current?->id ? 'Edit Plan' : 'New Plan' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Plan Name</label>
                        <input type="text" class="form-control" wire:model="data.name" placeholder="Plan name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price per Month</label>
                        <input type="number" class="form-control" wire:model="data.price_month" placeholder="Monthly price">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price per Year</label>
                        <input type="number" class="form-control" wire:model="data.price_year" placeholder="Yearly price">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Features</label>
                        <textarea class="form-control" wire:model="data.features" rows="3"></textarea>

                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" wire:model="data.recommended" id="planRecommended">
                        <label class="form-check-label" for="planRecommended">Recommended</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                </button>

                <button class="btn btn-primary" wire:click="save">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

</div>
