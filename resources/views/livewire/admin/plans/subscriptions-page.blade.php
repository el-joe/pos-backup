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
                    <div class="d-flex flex-wrap gap-2">
                        @if($currentSubscription->canRenew() && adminCan('subscriptions.renew'))
                            <button class="btn btn-success" wire:click="renewSubscription">
                                <i class="fa fa-sync me-1"></i> {{ __('general.pages.subscriptions.renew') }}
                            </button>
                        @endif

                        <a href="{{ $this->changePlanUrl($isMonthly ? 'annual' : 'monthly') }}" class="btn btn-outline-primary">
                            <i class="fa fa-right-left me-1"></i> {{ __('general.pages.subscriptions.change_plan') }}
                        </a>

                        @if($isMonthly)
                            <a href="{{ $this->changePlanUrl('annual') }}" class="btn btn-warning">
                                <i class="fa fa-arrow-up me-1"></i> {{ __('general.pages.subscriptions.upgrade_to_annual') }}
                                <span class="badge bg-dark ms-1">{{ __('general.pages.subscriptions.save_percent', ['percent' => 15]) }}</span>
                            </a>
                        @endif
                    </div>

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

</div>
