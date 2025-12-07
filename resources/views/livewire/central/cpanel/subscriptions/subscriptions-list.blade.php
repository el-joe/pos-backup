<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Subscriptions List</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tenant</th>
                            <th>Plan Name</th>
                            <th>Details</th>
                            <th>Price</th>
                            <th>start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Payment method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($subscriptions as $subscription)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $subscription->tenant->name }}</td>
                                <td>{{ $subscription->plan->name }}</td>
                                <td>{{ $subscription->details }}</td>
                                <td>{{ $subscription->price }}</td>
                                <td>{{ $subscription->start_date }}</td>
                                <td>{{ $subscription->end_date }}</td>
                                <span class="badge bg-{{ $subscription->statusColor() }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                                <td>{{ $subscription->payment_method }}</td>
                                <td>
                                    <a href="#" class="text-decoration-none text-inverse"><i
                                            class="bi bi-search"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination center aligned (optional) --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $subscriptions->links() }}
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
