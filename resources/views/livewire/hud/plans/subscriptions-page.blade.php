<div class="row gx-4 py-5">

    @if($currentSubscription)
        <!-- CURRENT SUBSCRIPTION -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Current Subscription</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p><strong>Plan:</strong> {{ $currentSubscription->plan->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Start Date:</strong> {{ carbon($currentSubscription->start_date)->format('Y-m-d') }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Expiration Date:</strong> {{ carbon($currentSubscription->end_date)->format('Y-m-d') }}</p>
                            </div>
                        </div>

                        @if($currentSubscription->canCancel() && adminCan('subscriptions.cancel'))
                            <button class="btn btn-danger" wire:click="cancelSubscription">
                                <i class="fa fa-times"></i> Cancel
                            </button>
                        @elseif($currentSubscription->canRenew() && adminCan('subscriptions.renew'))
                            <button class="btn btn-primary" wire:click="renewSubscription">
                                <i class="fa fa-sync"></i> Renew
                            </button>
                        @endif


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
    @endif
    <!-- PREVIOUS SUBSCRIPTIONS TABLE -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Previous Subscriptions</h5>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Plan</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subscription->plan?->name }}</td>
                                    <td>{{ carbon($subscription->start_date)->format('Y-m-d') }}</td>
                                    <td>{{ carbon($subscription->end_date)->format('Y-m-d') }}</td>
                                    <td>{{ $subscription->status }}</td>
                                    <td>${{ number_format($subscription->price, 2) }}</td>
                                </tr>
                            @endforeach
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
