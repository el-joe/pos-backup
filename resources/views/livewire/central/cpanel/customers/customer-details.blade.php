<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tenant Details: {{ $tenant->id }}</h5>
            <a class="btn btn-outline-theme" href="{{ route('cpanel.customers.list') }}">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-semibold">Company Name</div>
                    <div>{{ $tenant->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">Company Email</div>
                    <div>{{ $tenant->email ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">Company Phone</div>
                    <div>{{ $tenant->phone ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">Country</div>
                    <div>{{ $tenant->country()?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">Domain</div>
                    <div>{{ $tenant->domains->first()->domain ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">Active</div>
                    <div>
                        <span class="badge bg-{{ ($tenant->active ?? false) ? 'success' : 'secondary' }}">
                            {{ ($tenant->active ?? false) ? 'Yes' : 'No' }}
                        </span>
                    </div>
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

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h6 class="mb-0">Renew Package</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Period</label>
                        <select class="form-select" wire:model.defer="renewData.period">
                            <option value="month">Monthly</option>
                            <option value="year">Yearly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Systems</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="pos" wire:model.defer="renewData.systems_allowed" id="renewSystemPos">
                                <label class="form-check-label" for="renewSystemPos">POS</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="hrm" wire:model.defer="renewData.systems_allowed" id="renewSystemHrm">
                                <label class="form-check-label" for="renewSystemHrm">HRM</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="booking" wire:model.defer="renewData.systems_allowed" id="renewSystemBooking">
                                <label class="form-check-label" for="renewSystemBooking">Booking</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="button" wire:click="renewPackage">
                            <i class="fa fa-refresh"></i> Renew Current Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h6 class="mb-0">Upgrade Package</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">ERP Plan</label>
                        <select class="form-select" wire:model.defer="upgradeData.selected_plans.pos">
                            <option value="">-- Select --</option>
                            @foreach(($plansByModule['pos'] ?? []) as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">HRM Plan</label>
                        <select class="form-select" wire:model.defer="upgradeData.selected_plans.hrm">
                            <option value="">-- Select --</option>
                            @foreach(($plansByModule['hrm'] ?? []) as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Booking Plan</label>
                        <select class="form-select" wire:model.defer="upgradeData.selected_plans.booking">
                            <option value="">-- Select --</option>
                            @foreach(($plansByModule['booking'] ?? []) as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Period</label>
                        <select class="form-select" wire:model.defer="upgradeData.period">
                            <option value="month">Monthly</option>
                            <option value="year">Yearly</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-warning" type="button" wire:click="upgradePackage">
                            <i class="fa fa-level-up"></i> Upgrade Plans
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Subscription History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Plan</th>
                            <th>Price</th>
                            <th>Billing</th>
                            <th>Systems</th>
                            <th>Discount</th>
                            <th>Trial</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $subscription)
                            @php
                                $selectedSystemPlans = collect(data_get($subscription->plan_details, 'selected_system_plans', []));
                                $multiPlanLabel = $selectedSystemPlans
                                    ->map(function ($item) {
                                        $module = strtoupper((string) ($item['module'] ?? '-'));
                                        $name = (string) ($item['name'] ?? '-');
                                        return $module . ': ' . $name;
                                    })
                                    ->implode(' | ');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $multiPlanLabel ?: ($subscription->plan?->name ?? '-') }}</td>
                                <td>{{ $subscription->price }} {{ $subscription->currency?->code ?? '' }}</td>
                                <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                                <td>{{ implode(', ', $subscription->systems_allowed ?? []) ?: '-' }}</td>
                                <td>{{ number_format((float) data_get($subscription->plan_details, 'pricing.total_discount_amount', 0), 2) }}</td>
                                <td>{{ (int) data_get($subscription->plan_details, 'pricing.free_trial_months', 0) }} month(s)</td>
                                <td>{{ $subscription->start_date }}</td>
                                <td>{{ $subscription->end_date }}</td>
                                <td>
                                    <span class="badge bg-{{ $subscription->statusColor() }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No subscriptions found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
