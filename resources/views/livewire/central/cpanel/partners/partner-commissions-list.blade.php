<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.partner-commissions') }} ({{ $commissions->total() }})</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Partner</th>
                            <th>Tenant</th>
                            <th>Subscription</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Commission Date</th>
                            <th>Collected At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commissions as $commission)
                            <tr>
                                <td>{{ $commission->id }}</td>
                                <td>{{ $commission->partner?->name ?? '-' }}</td>
                                <td>{{ $commission->tenant_id ?? '-' }}</td>
                                <td>{{ $commission->subscription_id ?? '-' }}</td>
                                <td>{{ $commission->currency?->code ?? '-' }}</td>
                                <td>{{ $commission->amount }}</td>
                                <td>
                                    <span class="badge bg-{{ $commission->status === 'collected' ? 'success' : 'warning' }}">
                                        {{ $commission->status ?? 'pending' }}
                                    </span>
                                </td>
                                <td>{{ $commission->commission_date?->format('Y-m-d H:i') }}</td>
                                <td>{{ $commission->collected_at?->format('Y-m-d H:i') }}</td>
                                <td class="text-center">
                                    @if (!$commission->collected_at)
                                        <button class="btn btn-sm btn-success me-1" wire:click="markCollectedAlert({{ $commission->id }})" title="Mark collected">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $commission->id }})" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $commissions->links("pagination::bootstrap-5") }}
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
