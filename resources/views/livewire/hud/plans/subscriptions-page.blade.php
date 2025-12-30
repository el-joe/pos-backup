<div class="row gx-4 py-5">

    {{-- TOP SUMMARY CARDS --}}
    <div class="row mb-4">
        {{-- CURRENT SUBSCRIPTION --}}
        @if($currentSubscription)
        @php
            $totalDays     = carbon($currentSubscription->start_date)->diffInDays(carbon($currentSubscription->end_date));
            $usedDays      = ceil(carbon($currentSubscription->start_date)->diffInDays(now()));
            $remainingDays = max($totalDays - $usedDays, 0);
            $progress      = $totalDays > 0 ? min(($usedDays / $totalDays) * 100, 100) : 0;
        @endphp

        <div class="col-8 mb-4">
            <div class="card shadow-sm overflow-hidden">

                {{-- STATUS BAR --}}
                <div class="bg-primary text-white px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fa fa-crown me-2"></i>
                        <strong>Active Subscription</strong>
                    </div>
                    <span class="badge bg-light text-primary">
                        {{ ucfirst($currentSubscription->status) }}
                    </span>
                </div>

                <div class="card-body">

                    {{-- PLAN INFO --}}
                    <div class="row text-center mb-4">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">Plan</div>
                            <div class="fw-bold fs-5">
                                {{ $currentSubscription->plan?->name }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">Price</div>
                            <div class="fw-bold fs-5">
                                {{ currencyFormat($currentSubscription->price, true) }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">Start Date</div>
                            <div class="fw-semibold">
                                {{ dateTimeFormat($currentSubscription->start_date,true,false) }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">End Date</div>
                            <div class="fw-semibold">
                                {{ dateTimeFormat($currentSubscription->end_date,true,false) }}
                            </div>
                        </div>
                    </div>

                    {{-- PROGRESS --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Subscription Progress</small>
                            <small class="fw-semibold text-primary">
                                {{ $remainingDays }} days remaining
                            </small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div
                                class="progress-bar bg-success"
                                style="width: {{ $progress }}%">
                            </div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="d-flex flex-wrap gap-2">
                        @if($currentSubscription->canRenew() && adminCan('subscriptions.renew'))
                            <button class="btn btn-success" wire:click="renewSubscription">
                                <i class="fa fa-sync me-1"></i> Renew
                            </button>
                        @endif

                        @if($currentSubscription->canCancel() && adminCan('subscriptions.cancel'))
                            <button class="btn btn-outline-danger" wire:click="cancelSubscription">
                                <i class="fa fa-times me-1"></i> Cancel & Refund
                            </button>
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
                        <i class="fa fa-wallet me-2"></i> Account Balance
                    </h5>
                </div>

                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-muted mb-2">Available Balance</div>
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
                        <i class="fa fa-history me-2"></i> Previous Subscriptions
                    </h5>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Plan</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subscription->plan?->name }}</td>
                                    <td>{{ dateTimeFormat($subscription->start_date,true,false) }}</td>
                                    <td>{{ dateTimeFormat($subscription->end_date,true,false) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
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
                                        No previous subscriptions found
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
