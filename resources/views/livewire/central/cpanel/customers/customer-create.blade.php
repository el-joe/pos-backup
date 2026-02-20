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
                    <label class="form-label">Plan</label>
                    <select class="form-select" wire:model.defer="data.plan_id">
                        <option value="">-- Select --</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Billing Period</label>
                    <select class="form-select" wire:model.defer="data.period">
                        <option value="month">Monthly</option>
                        <option value="year">Yearly</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label d-block">Allowed Systems</label>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="pos" wire:model.defer="data.systems_allowed" id="createSystemPos">
                            <label class="form-check-label" for="createSystemPos">POS</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="hrm" wire:model.defer="data.systems_allowed" id="createSystemHrm">
                            <label class="form-check-label" for="createSystemHrm">HRM</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="booking" wire:model.defer="data.systems_allowed" id="createSystemBooking">
                            <label class="form-check-label" for="createSystemBooking">Booking</label>
                        </div>
                    </div>
                    @error('data.systems_allowed') <small class="text-danger">{{ $message }}</small> @enderror
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
