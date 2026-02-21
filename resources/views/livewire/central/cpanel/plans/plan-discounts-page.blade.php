<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Plan Free Trial Settings</h5>
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
                            <th>3 Months Free</th>
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
                                <td>
                                    @if($plan->three_months_free)
                                        <span class="badge bg-success">Enabled</span>
                                    @else
                                        <span class="badge bg-secondary">Disabled</span>
                                    @endif
                                </td>
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
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="threeMonthsFreeCheck" wire:model.defer="data.is_three_month_trial">
                        <label class="form-check-label" for="threeMonthsFreeCheck">
                            Enable 3 months free
                        </label>
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
