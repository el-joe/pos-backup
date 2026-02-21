<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create New Tenant</h5>
            <a class="btn btn-outline-theme" href="{{ route('cpanel.customers.list') }}">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-12"><h6 class="mb-0">Company Information</h6></div>

                <div class="col-md-6">
                    <label class="form-label">Company Name</label>
                    <input type="text" class="form-control" wire:model.defer="data.company_name">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Domain</label>
                    <input type="text" class="form-control" wire:model.defer="data.domain" placeholder="tenant.yourdomain.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Company Email</label>
                    <input type="email" class="form-control" wire:model.defer="data.company_email">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Company Phone</label>
                    <input type="text" class="form-control" wire:model.defer="data.company_phone">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <select class="form-select" wire:model.defer="data.country_id">
                        <option value="">-- Select --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Currency</label>
                    <select class="form-select" wire:model.defer="data.currency_id">
                        <option value="">-- Select --</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tax Number</label>
                    <input type="text" class="form-control" wire:model.defer="data.tax_number">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Active</label>
                    <select class="form-select" wire:model.defer="data.active">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea rows="2" class="form-control" wire:model.defer="data.address"></textarea>
                </div>

                <div class="col-12 mt-2"><h6 class="mb-0">Admin Account</h6></div>

                <div class="col-md-6">
                    <label class="form-label">Admin Name</label>
                    <input type="text" class="form-control" wire:model.defer="data.admin_name">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Admin Email</label>
                    <input type="email" class="form-control" wire:model.defer="data.admin_email">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Admin Phone</label>
                    <input type="text" class="form-control" wire:model.defer="data.admin_phone">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Admin Password</label>
                    <input type="text" class="form-control" wire:model.defer="data.admin_password">
                </div>

                <div class="col-12 mt-2"><h6 class="mb-0">Subscription Package</h6></div>

                <div class="col-md-6">
                    <label class="form-label">Billing Period</label>
                    <select class="form-select" wire:model.defer="data.period">
                        <option value="month">Monthly</option>
                        <option value="year">Yearly</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">POS Plan</label>
                    <select class="form-select" wire:model.defer="data.selected_plans.pos">
                        <option value="">-- Select --</option>
                        @foreach(($plansByModule['pos'] ?? collect()) as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                    @error('data.selected_plans.pos') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">HRM Plan (Optional)</label>
                    <select class="form-select" wire:model.defer="data.selected_plans.hrm">
                        <option value="">-- Not selected --</option>
                        @foreach(($plansByModule['hrm'] ?? collect()) as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                    @error('data.selected_plans.hrm') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Booking Plan (Optional)</label>
                    <select class="form-select" wire:model.defer="data.selected_plans.booking">
                        <option value="">-- Not selected --</option>
                        @foreach(($plansByModule['booking'] ?? collect()) as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                    @error('data.selected_plans.booking') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-12">
                    <small class="text-muted">
                        POS plan is required. HRM and Booking can be added optionally to create a multi-system subscription.
                    </small>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                        <i class="fa fa-save"></i> Create Tenant
                    </button>
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
</div>
