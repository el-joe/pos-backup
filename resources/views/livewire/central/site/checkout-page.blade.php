<div class="container py-5">

    <div class="row gy-4">

        <!-- ========================= -->
        <!--  LEFT: PLAN DETAILS CARD -->
        <!-- ========================= -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Plan Details</h5>
                        <small class="text-muted">Review the selected subscription plan features</small>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Plan Summary -->
                    <div class="d-flex align-items-center justify-content-between mb-3 w-100">

                        <!-- Left Side (Icon + Title) -->
                        <div class="d-flex align-items-center">
                            <span class="{{ $plan->icon }} display-4 text-body text-opacity-50 rounded-3 me-3"></span>
                            <div>
                                <h6 class="mb-0">{{ $plan->name ?? 'Pro Plan' }}</h6>
                            </div>
                        </div>

                        <!-- Right Side (Price) -->
                        <div class="text-end">
                            <small class="text-muted">Price</small>
                            <h5 class="mb-0">
                                {{ $plan->{"price_".$period} }}$
                                <small class="text-muted">/ {{ $period }}</small>
                            </h5>
                        </div>
                    </div>

                    <hr>

                    <!-- Features Table -->
                    <h6 class="fw-bold">Limits & Features</h6>
                    <table class="table table-borderless mb-0">
                        @foreach ($plan->features as $title=>$feature)
                            @php $featureEnum = \App\Enums\PlanFeaturesEnum::from($title); @endphp
                            <tr>
                                <td class="text-muted">{{ $featureEnum->label() }}</td>
                                @if(!($feature['description'] ?? false))
                                    <td><i class="{{ $feature['status'] ? 'fa fa-check text-theme' : 'fa fa-times text-body text-opacity-25' }} fa-lg"></i></td>
                                @else
                                    <td><strong>{{ $feature['description'] ?? '' }}</strong></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>

                    <hr>

                    <div class="mt-3 d-flex gap-2">
                        <a href="/#pricing" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-edit"></i> Change Plan
                        </a>
                        <a href="{{ route('pricing-compare') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-list"></i> Compare Plans
                        </a>
                    </div>

                </div>

                <!-- Card Arrows -->
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <!-- ========================= -->
        <!-- RIGHT: COMPANY DETAILS FORM -->
        <!-- ========================= -->
        <div class="col-12">
            <div class="card shadow-sm">

                <div class="card-header">
                    <h5 class="mb-0">Company Details</h5>
                    <small class="text-muted">Enter your company information to create an account</small>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label">Company Name *</label>
                            <input required wire:model="data.company_name" class="form-control" placeholder="Example: Nile Trading">
                            @error('data.company_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label">Company Email *</label>
                            <input required wire:model="data.company_email" type="email" class="form-control" placeholder="example@example.com">
                            @error('data.company_email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label">Company Phone *</label>
                            <input type="text" required wire:model="data.company_phone" class="form-control" placeholder="0123456789">
                            @error('data.company_phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Domain Selection -->
                        @if($plan->{'price_'.$period} > 0)
                        <div class="col-12">
                            <label class="form-label">Domain Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="domain_mode" id="mode_subdomain" wire:click="$set('data.domain_mode', 'subdomain')" checked>
                                    <label class="form-check-label" for="mode_subdomain">Subdomain</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="domain_mode" id="mode_domain" wire:click="$set('data.domain_mode', 'domain')">
                                    <label class="form-check-label" for="mode_domain">Custom Domain</label>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(!isset($data['domain_mode']) || $data['domain_mode'] == 'subdomain')
                        <div class="col-12" id="group_subdomain">
                            <label class="form-label">Subdomain</label>
                            <input type="text" class="form-control form-control-lg" id="subdomain_input" wire:model.live.debounce.500ms="data.subdomain" placeholder="yourname">
                            <small class="text-muted" id="domain_preview">Will be: {{  $data['final_domain'] ?? '--' }}</small>
                            @error('data.subdomain') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        @else
                        <div class="col-12" id="group_domain">
                            <label class="form-label">Custom Domain</label>
                            <input type="text" class="form-control form-control-lg" id="domain_text_input" wire:model.live="data.domain" placeholder="example.com">
                            @error('data.domain') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <select class="form-select" wire:model="data.country_id" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('data.country_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Currency</label>
                            <select class="form-select" wire:model="data.currency_id" required>
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }} ( {{ $currency->code }} )</option>
                                @endforeach
                            </select>
                            @error('data.currency_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">VAT Number (optional)</label>
                            <input wire:model="data.tax_number" class="form-control">
                            @error('data.tax_number') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input wire:model="data.address" class="form-control" placeholder="Full address">
                            @error('data.address') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <hr class="mt-4">

                        <h6 class="fw-bold">Admin Details</h6>

                        <div class="col-md-6">
                            <label class="form-label">Admin Name *</label>
                            <input required wire:model="data.admin_name" class="form-control">
                            @error('data.admin_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Admin Email *</label>
                            <input required wire:model="data.admin_email" type="email" class="form-control">
                            @error('data.admin_email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Admin Phone *</label>
                            <input wire:model="data.admin_phone" class="form-control">
                            @error('data.admin_phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password *</label>
                            <input required wire:model="data.admin_password" type="password" class="form-control">
                            @error('data.admin_password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

                <!-- Card Arrows -->
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <!-- ========================= -->
        <!-- FULL-WIDTH CHECKOUT SUMMARY -->
        <!-- ========================= -->
        <div class="col-12">
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Checkout Summary</h5>
                    <small class="text-muted">Invoice details before payment</small>
                </div>

                <div class="card-body">

                    {{-- <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Coupon Code</label>
                            <div class="input-group">
                                <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Enter coupon code">
                                <button class="btn btn-outline-primary" type="button" id="apply_coupon_btn">Apply</button>
                            </div>
                            <small class="text-muted">Enter a coupon code to reduce the total price</small>
                        </div>
                    </div> --}}

                    @if($plan->{'price_'.$period} > 0)
                        <table class="table">
                            <tr>
                                <td>Plan:</td>
                                <td>{{ $plan->name ?? 'Pro Plan' }}</td>
                            </tr>
                            <tr>
                                <td>Period:</td>
                                <td>{{ $period }}</td>
                            </tr>
                            <tr>
                                <td>Price:</td>
                                <td>${{ $plan->{"price_" . $period} }}</td>
                            </tr>
                            <tr>
                                <td>Discount:</td>
                                <td id="discount_amount">$0</td>
                            </tr>
                            {{-- <tr>
                                <td>VAT:</td>
                                <td id="vat_amount">$0</td>
                            </tr> --}}
                            <tr class="table-dark fw-bold">
                                <td>Total:</td>
                                <td id="total_amount">${{ $plan->{"price_" . $period} }}</td>
                            </tr>
                        </table>
                    @endif

                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-primary px-4" wire:click="completeSubscription">
                        <i class="fa fa-credit-card"></i> Complete Subscription
                    </button>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

    </div>

</div>
