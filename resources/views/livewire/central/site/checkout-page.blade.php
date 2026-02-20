<div class="container py-5">

    <div class="row gy-4">

        <!-- ========================= -->
        <!--  LEFT: PLAN DETAILS CARD -->
        <!-- ========================= -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ __('website.checkout.plan_details_title') }}</h5>
                        <small class="text-muted">{{ __('website.checkout.plan_details_subtitle') }}</small>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Plan Summary -->
                    <div class="d-flex align-items-center justify-content-between mb-3 w-100">

                        <!-- Left Side (Icon + Title) -->
                        <div class="d-flex align-items-center">
                            <span class="{{ $plan->icon }} display-4 text-body text-opacity-50 rounded-3 me-3"></span>
                            <div>
                                <h6 class="mb-0">{{ $plan->name ?? __('website.checkout.pro_plan') }}</h6>
                            </div>
                        </div>

                        <!-- Right Side (Price) -->
                        <div class="text-end">
                            <small class="text-muted">{{ __('website.checkout.price') }}</small>
                            <h5 class="mb-0">
                                {{ currencySymbolPosition(($pricingSummary['final_price'] ?? 0) * ($currentCurrency->conversion_rate ?? 1), $currentCurrency->symbol) }}
                                <small class="text-muted">/ {{ __('website.checkout.periods.' . $period) }}</small>
                            </h5>
                            @if(($pricingSummary['free_trial_months'] ?? 0) > 0)
                                <small class="text-success d-block">Free for {{ (int) $pricingSummary['free_trial_months'] }} month(s). Pay nothing during trial.</small>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Features Table -->
                    <h6 class="fw-bold">{{ __('website.checkout.limits_features') }}</h6>
                    <table class="table table-borderless mb-0">
                        @foreach ($plan->features as $title=>$feature)
                            @php $featureEnum = \App\Enums\PlanFeaturesEnum::tryFrom($title); @endphp
                            <tr>
                                <td class="text-muted">{{ $featureEnum?->label() ?? \Illuminate\Support\Str::headline(str_replace('_', ' ', (string) $title)) }}</td>
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
                            <i class="fa fa-edit"></i> {{ __('website.checkout.change_plan') }}
                        </a>
                        <a href="{{ route('pricing-compare') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-list"></i> {{ __('website.checkout.compare_plans') }}
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
                    <h5 class="mb-0">{{ __('website.checkout.company_details_title') }}</h5>
                    <small class="text-muted">{{ __('website.checkout.company_details_subtitle') }}</small>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label">{{ __('website.checkout.company_name') }} *</label>
                            <input required wire:model="data.company_name" class="form-control" placeholder="{{ __('website.checkout.company_name_placeholder') }}">
                            @error('data.company_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label">{{ __('website.checkout.company_email') }} *</label>
                            <input required wire:model="data.company_email" type="email" class="form-control" placeholder="{{ __('website.checkout.company_email_placeholder') }}">
                            @error('data.company_email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label">{{ __('website.checkout.company_phone') }} *</label>
                            <input type="text" required wire:model="data.company_phone" class="form-control" placeholder="{{ __('website.checkout.company_phone_placeholder') }}">
                            @error('data.company_phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Domain Selection -->
                        @if(($pricingSummary['final_price'] ?? 0) > 0)
                        <div class="col-12">
                            <label class="form-label">{{ __('website.checkout.domain_type') }}</label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="domain_mode" id="mode_subdomain" wire:click="$set('data.domain_mode', 'subdomain')" checked>
                                    <label class="form-check-label" for="mode_subdomain">{{ __('website.checkout.subdomain') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="domain_mode" id="mode_domain" wire:click="$set('data.domain_mode', 'domain')">
                                    <label class="form-check-label" for="mode_domain">{{ __('website.checkout.custom_domain') }}</label>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(!isset($data['domain_mode']) || $data['domain_mode'] == 'subdomain')
                        <div class="col-12" id="group_subdomain">
                            <label class="form-label">{{ __('website.checkout.subdomain') }}</label>
                            <input type="text" class="form-control form-control-lg" id="subdomain_input" wire:model.live.debounce.500ms="data.subdomain" placeholder="{{ __('website.checkout.subdomain_placeholder') }}">
                            <small class="text-muted" id="domain_preview">{{ __('website.checkout.domain_preview') }}: {{  $data['final_domain'] ?? '--' }}</small>
                            @error('data.subdomain') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        @else
                        <div class="col-12" id="group_domain">
                            <label class="form-label">{{ __('website.checkout.custom_domain') }}</label>
                            <input type="text" class="form-control form-control-lg" id="domain_text_input" wire:model.live="data.domain" placeholder="{{ __('website.checkout.custom_domain_placeholder') }}">
                            @error('data.domain') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label">{{ __('website.checkout.country') }}</label>
                            <select class="form-select" wire:model="data.country_id" required>
                                <option value="">{{ __('website.checkout.select_country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('data.country_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('website.checkout.currency') }}</label>
                            <select class="form-select" wire:model.live="data.currency_id" required>
                                <option value="">{{ __('website.checkout.select_currency') }}</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }} ( {{ $currency->code }} )</option>
                                @endforeach
                            </select>
                            @error('data.currency_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">{{ __('website.checkout.vat_number_optional') }}</label>
                            <input wire:model="data.tax_number" class="form-control">
                            @error('data.tax_number') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label d-block">Systems *</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="pos" wire:model.live="data.systems_allowed" id="checkoutSystemPos">
                                    <label class="form-check-label" for="checkoutSystemPos">POS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="hrm" wire:model.live="data.systems_allowed" id="checkoutSystemHrm">
                                    <label class="form-check-label" for="checkoutSystemHrm">HRM</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="booking" wire:model.live="data.systems_allowed" id="checkoutSystemBooking">
                                    <label class="form-check-label" for="checkoutSystemBooking">Booking</label>
                                </div>
                            </div>
                            @error('data.systems_allowed') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('website.checkout.address') }}</label>
                            <input wire:model="data.address" class="form-control" placeholder="{{ __('website.checkout.address_placeholder') }}">
                            @error('data.address') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <hr class="mt-4">

                        <h6 class="fw-bold">{{ __('website.checkout.admin_details') }}</h6>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('website.checkout.admin_name') }} *</label>
                            <input required wire:model="data.admin_name" class="form-control">
                            @error('data.admin_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('website.checkout.admin_email') }} *</label>
                            <input required wire:model="data.admin_email" type="email" class="form-control">
                            @error('data.admin_email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('website.checkout.admin_phone') }} *</label>
                            <input wire:model="data.admin_phone" class="form-control">
                            @error('data.admin_phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('website.checkout.password') }} *</label>
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
                    <h5 class="mb-0">{{ __('website.checkout.summary_title') }}</h5>
                    <small class="text-muted">{{ __('website.checkout.summary_subtitle') }}</small>
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

                    @if(($pricingSummary['base_price'] ?? 0) > 0)
                        <table class="table">
                            <tr>
                                <td>{{ __('website.checkout.plan') }}:</td>
                                <td>{{ $plan->name ?? __('website.checkout.pro_plan') }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('website.checkout.period') }}:</td>
                                <td>{{ __('website.checkout.periods.' . $period) }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('website.checkout.price') }}:</td>
                                @if($currentCurrency->conversion_rate && $currentCurrency->conversion_rate != 1)
                                    <td>
                                        {{ currencySymbolPosition(number_format(($pricingSummary['base_price'] ?? 0) * $currentCurrency->conversion_rate, 2), $currentCurrency->symbol) }}
                                    </td>
                                @else
                                    <td>
                                        {{ currencySymbolPosition(($pricingSummary['base_price'] ?? 0) * ($currentCurrency->conversion_rate ?? 1), '$') }}
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>{{ __('website.checkout.discount') }}:</td>
                                <td id="discount_amount">
                                    @if($currentCurrency->conversion_rate && $currentCurrency->conversion_rate != 1)
                                        {{ currencySymbolPosition(number_format(($pricingSummary['total_discount_amount'] ?? 0) * $currentCurrency->conversion_rate, 2), $currentCurrency->symbol) }}
                                    @else
                                        {{ currencySymbolPosition(($pricingSummary['total_discount_amount'] ?? 0) * ($currentCurrency->conversion_rate ?? 1), '$') }}
                                    @endif
                                </td>
                            </tr>
                            @if(($pricingSummary['free_trial_months'] ?? 0) > 0)
                            <tr>
                                <td>Free Trial:</td>
                                <td>{{ (int) $pricingSummary['free_trial_months'] }} month(s)</td>
                            </tr>
                            @endif
                            {{-- <tr>
                                <td>VAT:</td>
                                <td id="vat_amount">$0</td>
                            </tr> --}}
                            <tr class="table-dark fw-bold">
                                <td>{{ __('website.checkout.total') }}:</td>
                                <td id="total_amount">{{ currencySymbolPosition(($pricingSummary['final_price'] ?? 0) * ($currentCurrency->conversion_rate ?? 1), $currentCurrency->symbol) }}</td>
                            </tr>
                            <tr>
                                <td>Due now:</td>
                                <td>{{ currencySymbolPosition(((int) ($pricingSummary['free_trial_months'] ?? 0) > 0 ? 0 : (($pricingSummary['final_price'] ?? 0) * ($currentCurrency->conversion_rate ?? 1))), $currentCurrency->symbol) }}</td>
                            </tr>
                        </table>
                        @if($currentCurrency->country_code != 'EGP')
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                {{ __('website.checkout.currency_conversion_note', ['currency' => $currentCurrency->code, 'symbol' => $currentCurrency->symbol,'usd' => ($pricingSummary['final_price'] ?? 0)]) }}
                            </div>
                        @endif
                    @endif

                        <!-- Agreements: Privacy Policy & Terms -->
                        <div class="col-12 mt-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="privacy_policy_agree" wire:model.live="data.privacy_policy_agree">
                                <label class="form-check-label" for="privacy_policy_agree">
                                    {!! __('website.i_agree_to_the_privacy_policy') !!} *
                                </label>
                            </div>
                            @error('data.privacy_policy_agree') <small class="text-danger">{{ $message }}</small> @enderror
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms_conditions_agree" wire:model.live="data.terms_conditions_agree">
                                <label class="form-check-label" for="terms_conditions_agree">
                                    {!! __('website.i_agree_to_the_terms_conditions') !!} *
                                </label>
                            </div>
                            @error('data.terms_conditions_agree') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                </div>
                <div class="card-footer d-flex justify-content-end">
                        <button class="btn btn-primary px-4" wire:click="completeSubscription"
                            @if(empty($data['privacy_policy_agree']) || empty($data['terms_conditions_agree'])) disabled @endif>
                            <i class="fa fa-credit-card"></i> {{ __('website.checkout.complete_subscription') }}
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
