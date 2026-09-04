<div class="row gx-4 py-5">

    {{-- TOP SUMMARY CARDS --}}
    <div class="row mb-4">
        {{-- CURRENT SUBSCRIPTION --}}
        @if($currentSubscription)
        @php
            $statusBadge = [
                'paid' => 'success',
                'expired' => 'danger',
                'cancelled' => 'warning',
            ][$currentSubscription->status] ?? 'secondary';

            $isMonthly = $currentSubscription->billing_cycle === 'monthly';
        @endphp

        @if($currentSubscription->status === 'paid' && !is_null($daysRemaining) && $daysRemaining < 30)
            <div class="col-12 mb-3">
                <div class="alert alert-warning d-flex align-items-center mb-0">
                    <i class="fa fa-triangle-exclamation me-2"></i>
                    {{ __('general.pages.subscriptions.expires_in_days', ['days' => $daysRemaining]) }}
                </div>
            </div>
        @endif

        <div class="col-8 mb-4">
            <div class="card shadow-sm overflow-hidden">

                {{-- STATUS BAR --}}
                <div class="bg-primary text-white px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fa fa-crown me-2"></i>
                        <strong>{{ __('general.pages.subscriptions.active_subscription') }}</strong>
                    </div>
                    <span>
                        @if($currentSubscription->is_trial)
                            <span class="badge bg-info me-1">{{ __('general.pages.subscriptions.free_trial') }}</span>
                        @endif
                        <span class="badge bg-{{ $statusBadge }}">
                            {{ ucfirst($currentSubscription->status) }}
                        </span>
                    </span>
                </div>

                <div class="card-body">

                    {{-- PLAN INFO --}}
                    <div class="row text-center mb-4">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">{{ __('general.pages.subscriptions.plan') }}</div>
                            <div class="fw-bold fs-5">
                                {{ $currentSubscription->plan?->localizedName() }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">{{ __('general.pages.subscriptions.price') }}</div>
                            <div class="fw-bold fs-5">
                                {{ $currentSubscription->is_trial ? __('general.pages.subscriptions.free_trial') : currencyFormat($currentSubscription->price, true) }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">{{ __('general.pages.subscriptions.start_date') }}</div>
                            <div class="fw-semibold">
                                {{ dateTimeFormat($currentSubscription->start_date,true,false) }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">{{ __('general.pages.subscriptions.end_date') }}</div>
                            <div class="fw-semibold">
                                {{ dateTimeFormat($currentSubscription->end_date,true,false) }}
                            </div>
                        </div>
                    </div>

                    {{-- PROGRESS --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">{{ __('general.pages.subscriptions.subscription_progress') }}</small>
                            <small class="fw-semibold text-primary">
                                {{ __('general.pages.subscriptions.days_remaining', ['days' => $daysRemaining]) }}
                            </small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div
                                class="progress-bar bg-success"
                                style="width: {{ $percentRemaining }}%">
                            </div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    @if(adminCan('subscriptions.renew'))
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" wire:click="openChangePlanPanel">
                            <i class="fa fa-right-left me-1"></i> {{ __('general.pages.subscriptions.renew') }} / {{ __('general.pages.subscriptions.change_plan') }}
                        </button>
                    </div>
                    @endif

                </div>

                {{-- CARD ARROWS --}}
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>
        </div>
        @endif


        {{-- ACCOUNT BALANCE --}}
        <div class="col-lg-4 col-12 mb-3">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-wallet me-2"></i> {{ __('general.pages.subscriptions.account_balance') }}
                    </h5>
                </div>

                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-muted mb-2">{{ __('general.pages.subscriptions.available_balance') }}</div>
                    <div class="display-6 fw-bold text-primary">
                        {{ currencyFormat($accountBalance,true) }}
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
    </div>

    {{-- PREVIOUS SUBSCRIPTIONS --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-history me-2"></i> {{ __('general.pages.subscriptions.previous_subscriptions') }}
                    </h5>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('general.pages.subscriptions.plan') }}</th>
                                <th>{{ __('general.pages.subscriptions.start_date') }}</th>
                                <th>{{ __('general.pages.subscriptions.end_date') }}</th>
                                <th>{{ __('general.pages.subscriptions.status') }}</th>
                                <th class="text-end">{{ __('general.pages.subscriptions.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subscriptions as $subscription)
                                @php
                                    $rowBadge = [
                                        'paid' => 'success',
                                        'expired' => 'danger',
                                        'cancelled' => 'warning',
                                    ][$subscription->status] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subscription->plan?->localizedName() }}</td>
                                    <td>{{ dateTimeFormat($subscription->start_date,true,false) }}</td>
                                    <td>{{ dateTimeFormat($subscription->end_date,true,false) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $rowBadge }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        {{ currencyFormat($subscription->price, true) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        {{ __('general.pages.subscriptions.no_previous_subscriptions_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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

    @if($showChangePlanPanel)
    <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:key="change-plan-modal">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-right-left me-2"></i>{{ __('general.pages.subscriptions.renew') }} / {{ __('general.pages.subscriptions.change_plan') }}</h5>
                    <button type="button" class="btn-close" wire:click="closeChangePlanPanel"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('general.pages.subscriptions.plan') }}</label>
                        <select class="form-select" wire:model.live="selectedPlanId">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->localizedName() }} — {{ currencyFormat($plan->price, true) }} / {{ $plan->isYearly() ? 'year' : 'month' }}
                                </option>
                            @endforeach
                        </select>
                        @error('selectedPlanId') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    @if($pricingPreview)
                    <div class="alert alert-light border d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">{{ __('general.pages.subscriptions.total') }}</span>
                        <span class="fs-5 fw-bold text-primary">{{ currencyFormat($pricingPreview['final_price'] ?? 0, true) }}</span>
                    </div>
                    @endif

                    <label class="form-label fw-semibold">Payment Method</label>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="border rounded-3 p-3 d-flex flex-column align-items-center gap-2 h-100 cursor-pointer {{ $payFromBalance ? 'border-primary bg-primary-subtle' : '' }}" style="cursor:pointer">
                                <input type="radio" class="d-none" wire:click="$set('payFromBalance', true)">
                                <i class="fa fa-wallet fs-3 text-primary"></i>
                                <span class="fw-semibold text-center">{{ __('general.pages.subscriptions.account_balance') }}</span>
                                <span class="small text-muted">{{ currencyFormat($accountBalance, true) }} available</span>
                            </label>
                        </div>
                        @foreach($paymentMethods as $pm)
                        <div class="col-md-4">
                            <label class="border rounded-3 p-3 d-flex flex-column align-items-center gap-2 h-100 cursor-pointer {{ (!$payFromBalance && $selectedPaymentMethodId == $pm->id) ? 'border-primary bg-primary-subtle' : '' }}" style="cursor:pointer">
                                <input type="radio" class="d-none" wire:click="$set('selectedPaymentMethodId', {{ $pm->id }})">
                                <i class="fa fa-money-bill-wave fs-3 text-secondary"></i>
                                <span class="fw-semibold text-center">{{ $pm->name }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('payFromBalance') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                    @error('selectedPaymentMethodId') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                    @if(!$payFromBalance && ($selectedPaymentMethod->manual ?? false))
                    <div class="bg-light border rounded-3 p-3 mb-3">
                        <h6 class="fw-bold text-primary mb-1">Manual Payment</h6>
                        <p class="small text-muted mb-3">Please transfer the amount using the details below, then upload the receipt to continue.</p>

                        @php $details = $selectedPaymentMethod->details ?? []; $locale = app()->getLocale(); @endphp
                        @if(is_array($details) && count($details) > 0)
                        <div class="bg-white p-3 rounded border mb-3 small">
                            @foreach($details as $row)
                                @php
                                    $label = $row['label'][$locale] ?? ($row['label']['en'] ?? ($row['key'] ?? ''));
                                    $value = $row['value'][$locale] ?? ($row['value']['en'] ?? '');
                                @endphp
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="fw-semibold">{{ $label }}:</span>
                                    <span>{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        @if($selectedPaymentMethod->currency && $pricingPreview)
                        <div class="bg-white p-3 rounded border mb-3 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Amount to transfer</span>
                            <span class="fs-5 fw-bold text-primary">
                                {{ number_format(((float) ($pricingPreview['final_price'] ?? 0)) * (float) $selectedPaymentMethod->currency->conversion_rate, 2) }}
                                {{ $selectedPaymentMethod->currency->code }}
                            </span>
                        </div>
                        @endif

                        <label class="form-label fw-semibold">Upload Receipt <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" wire:model="receiptFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div wire:loading wire:target="receiptFile" class="small text-primary mt-1"><i class="fa fa-spinner fa-spin me-1"></i>Uploading...</div>
                        @error('receiptFile') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeChangePlanPanel">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="processSubscriptionChange" wire:loading.attr="disabled" wire:target="processSubscriptionChange">
                        <span wire:loading.remove wire:target="processSubscriptionChange"><i class="fa fa-check me-1"></i> Confirm</span>
                        <span wire:loading wire:target="processSubscriptionChange"><i class="fa fa-spinner fa-spin me-1"></i> Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
