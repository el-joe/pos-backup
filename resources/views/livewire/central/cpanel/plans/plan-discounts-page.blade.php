<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Plan Discounts & Free Trial</h5>
            <a class="btn btn-outline-theme" href="{{ route('cpanel.plans.list') }}">
                <i class="fa fa-gem"></i> Plans
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Plan</th>
                            <th>Module</th>
                            <th>Monthly</th>
                            <th>Yearly</th>
                            <th>Plan Discount %</th>
                            <th>Multi-System Discount %</th>
                            <th>Free Trial Months</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <td>{{ $plan->id }}</td>
                                <td>{{ $plan->name }}</td>
                                <td>{{ strtoupper((string) $plan->module_name?->value) }}</td>
                                <td>${{ $plan->price_month }}</td>
                                <td>${{ $plan->price_year }}</td>
                                <td>{{ number_format((float) ($plan->discount_percent ?? 0), 2) }}</td>
                                <td>{{ number_format((float) ($plan->multi_system_discount_percent ?? 0), 2) }}</td>
                                <td>{{ (int) ($plan->free_trial_months ?? 0) }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#discountModal" wire:click="edit({{ $plan->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $plans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="discountModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Plan Discount Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Plan Discount %</label>
                        <input type="number" step="0.01" class="form-control" wire:model.defer="data.discount_percent" min="0" max="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Multi-System Discount %</label>
                        <input type="number" step="0.01" class="form-control" wire:model.defer="data.multi_system_discount_percent" min="0" max="100">
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Free Trial Months</label>
                        <input type="number" class="form-control" wire:model.defer="data.free_trial_months" min="0" max="24">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
