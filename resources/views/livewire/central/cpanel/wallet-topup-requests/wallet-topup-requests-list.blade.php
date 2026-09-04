<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">Wallet Top-up Requests</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tenant</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topupRequests as $req)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $req->tenant?->id }}</td>
                                <td>${{ number_format((float) $req->amount, 2) }}</td>
                                <td>
                                    @if($req->manual)
                                        <span class="badge border border-dark text-dark"><i class="bi bi-cash me-1"></i>{{ $req->paymentMethod?->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $req->paymentMethod?->name }}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap small text-muted">{{ $req->created_at->translatedFormat('d M Y h:i A') }}</td>
                                <td>
                                    <span class="badge bg-{{ $req->status === 'pending' ? 'warning' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" wire:click="viewDetails({{ $req->id }})" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $topupRequests->links() }}
        </div>
    </div>

    @if($current)
    <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Wallet Top-up Request #{{ $current->id }}</h5>
                    <button type="button" class="btn-close" wire:click="closeDetails"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 220px;">Tenant</th>
                                <td>{{ $current->tenant?->id }} — {{ $current->tenant?->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Amount (USD)</th>
                                <td>${{ number_format((float) $current->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Payment Method</th>
                                <td><span class="badge bg-primary px-2 py-1 fs-6">{{ $current->paymentMethod?->name }}</span></td>
                            </tr>
                            @if($current->manual)
                            <tr>
                                <th class="bg-light">Manual Transfer</th>
                                <td><span class="badge border border-dark text-dark px-2 py-1"><i class="bi bi-cash me-1"></i> Manual Transfer</span></td>
                            </tr>
                            @endif
                            @if($current->currency_code && $current->converted_amount)
                            <tr>
                                <th class="bg-light">Converted Amount</th>
                                <td>
                                    <span class="badge bg-success px-2 py-1 fs-6">
                                        {{ number_format((float) $current->converted_amount, 2) }}
                                        {{ $current->currency_symbol ?? $current->currency_code }}
                                        ({{ $current->currency_code }})
                                    </span>
                                    <div class="small text-muted mt-1">Conversion rate: {{ $current->conversion_rate }}</div>
                                </td>
                            </tr>
                            @endif
                            @if($current->receipt_path)
                            <tr>
                                <th class="bg-light">Receipt</th>
                                <td>
                                    <a href="{{ asset('storage/' . $current->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-image"></i> View Receipt
                                    </a>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th class="bg-light">Status</th>
                                <td>
                                    <span class="badge bg-{{ $current->status === 'pending' ? 'warning' : ($current->status === 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($current->status) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDetails">Close</button>
                    @if($current->status === 'pending')
                    <button type="button" class="btn btn-success" wire:click="changeStatus({{ $current->id }}, 'approved')">
                        <i class="bi bi-check-lg"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="changeStatus({{ $current->id }}, 'rejected')">
                        <i class="bi bi-x-lg"></i> Reject
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
