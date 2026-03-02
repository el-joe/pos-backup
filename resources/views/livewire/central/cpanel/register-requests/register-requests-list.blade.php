<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">Register Requests</h5>

            <button class="btn btn-sm btn-primary d-flex align-items-center gap-2" wire:click="markAllAsRead"
                wire:loading.attr="disabled" wire:target="markAllAsRead">

                <span wire:loading.remove wire:target="markAllAsRead">
                    <i class="bi bi-check-all fs-6"></i>
                </span>
                <span class="spinner-border spinner-border-sm" wire:loading wire:target="markAllAsRead"></span>
                <span>Mark All Read</span>
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Time</th>
                            <th class="text-center">Read Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($registerRequests as $req)
                            @php
                                $d = $req->data;
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d['company']['name'] }}</td>
                                <td>{{ $d['company']['email'] }}</td>
                                <td>{{ $d['company']['phone'] }}</td>

                                <td class="text-nowrap small text-muted">
                                    {{ $req->created_at->translatedFormat('d M Y h:i A') }}
                                </td>

                                <td class="text-center">
                                    @if ($req->read_at)
                                        <div class="text-success fw-bold" style="font-size: 0.85rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            {{ \Carbon\Carbon::parse($req->read_at)->translatedFormat('d M h:i A') }}
                                        </div>
                                    @else
                                        <button class="btn btn-sm btn-outline-primary rounded-circle shadow-sm"
                                            style="width: 34px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center;"
                                            wire:click="markAsRead({{ $req->id }})" wire:loading.attr="disabled"
                                            title="Mark as Read">

                                            <i class="bi bi-eye-fill fs-6" wire:loading.remove
                                                wire:target="markAsRead({{ $req->id }})"></i>

                                            <span class="spinner-border spinner-border-sm"
                                                style="width: 1rem; height: 1rem;" wire:loading
                                                wire:target="markAsRead({{ $req->id }})"></span>
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $req->status === 'pending' ? 'warning' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button class="btn btn-sm btn-info text-white"
                                            wire:click="viewDetails({{ $req->id }})"
                                            title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        @if ($req->status === 'pending')
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="changeStatus({{ $req->id }}, 'rejected')"
                                                title="Reject">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success"
                                                wire:click="changeStatus({{ $req->id }}, 'approved')"
                                                title="Approve">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $registerRequests->links('pagination::bootstrap-5') }}
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

    <!-- Details Modal -->
    @if($selectedDetails)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registration Details</h5>
                    <button type="button" class="btn-close" wire:click="closeDetails" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Company Details -->
                        <div class="col-md-6">
                            <div class="card h-100 bg-light border-0">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3"><i class="bi bi-building"></i> Company</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tbody>
                                            <tr><th class="text-muted" style="width: 100px;">Name</th><td>{{ $selectedDetails['company']['name'] ?? 'N/A' }}</td></tr>
                                            <tr><th class="text-muted">Email</th><td>{{ $selectedDetails['company']['email'] ?? 'N/A' }}</td></tr>
                                            <tr><th class="text-muted">Phone</th><td>{{ $selectedDetails['company']['phone'] ?? 'N/A' }}</td></tr>
                                            <tr><th class="text-muted">Domain</th><td><code class="text-dark">{{ $selectedDetails['company']['domain'] ?? 'N/A' }}</code></td></tr>
                                            @if(!empty($selectedDetails['company']['tax_number']))
                                            <tr><th class="text-muted">Tax No</th><td>{{ $selectedDetails['company']['tax_number'] }}</td></tr>
                                            @endif
                                            @if(!empty($selectedDetails['company']['address']))
                                            <tr><th class="text-muted">Address</th><td>{{ $selectedDetails['company']['address'] }}</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Details -->
                        <div class="col-md-6">
                            <div class="card h-100 bg-light border-0">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3"><i class="bi bi-person-badge"></i> Admin</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tbody>
                                            <tr><th class="text-muted" style="width: 100px;">Name</th><td>{{ $selectedDetails['admin']['name'] ?? 'N/A' }}</td></tr>
                                            <tr><th class="text-muted">Email</th><td>{{ $selectedDetails['admin']['email'] ?? 'N/A' }}</td></tr>
                                            @if(!empty($selectedDetails['admin']['phone']))
                                            <tr><th class="text-muted">Phone</th><td>{{ $selectedDetails['admin']['phone'] }}</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Plan Details -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3"><i class="bi bi-box"></i> Plan Details</h6>

                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered bg-white mb-2">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-light" style="width: 200px;">Period</th>
                                                    <td><span class="badge bg-secondary">{{ ucfirst($selectedDetails['plan']['period'] ?? 'N/A') }}</span></td>
                                                </tr>
                                                @if(isset($selectedDetails['plan']['pricing']))
                                                <tr>
                                                    <th class="bg-light">Base Price</th>
                                                    <td>{{ $selectedDetails['plan']['pricing']['base_price'] ?? 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">Total Price</th>
                                                    <td class="fw-bold">{{ $selectedDetails['plan']['pricing']['total_price'] ?? 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">Free Trial</th>
                                                    <td>
                                                        @if(($selectedDetails['plan']['pricing']['is_free_trial'] ?? false))
                                                            <span class="badge bg-success">Yes ({{ $selectedDetails['plan']['pricing']['free_trial_months'] ?? 0 }} Months)</span>
                                                        @else
                                                            <span class="badge bg-secondary">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                                @if(isset($selectedDetails['plan']['systems_allowed']))
                                                <tr>
                                                    <th class="bg-light">Allowed Systems</th>
                                                    <td>
                                                        @foreach($selectedDetails['plan']['systems_allowed'] as $sys)
                                                            <span class="badge bg-info text-dark me-1">{{ strtoupper($sys) }}</span>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @endif
                                                @if(isset($selectedDetails['partner_id']) && $selectedDetails['partner_id'])
                                                <tr>
                                                    <th class="bg-light text-warning">Partner ID</th>
                                                    <td><span class="badge bg-warning text-dark">#{{ $selectedDetails['partner_id'] }}</span></td>
                                                </tr>
                                                @endif
                                                @if(isset($selectedDetails['payment']))
                                                <tr>
                                                    <th class="bg-light text-info">Payment Info</th>
                                                    <td>
                                                        @if(isset($selectedDetails['payment']['payment_method_name']))
                                                            <div class="mb-2"><span class="badge bg-primary px-2 py-1 fs-6">{{ $selectedDetails['payment']['payment_method_name'] }}</span></div>
                                                        @endif
                                                        @if(isset($selectedDetails['payment']['amount']))
                                                            <div class="mb-1 text-muted"><strong>Amount: </strong>{{ $selectedDetails['payment']['amount'] }}</div>
                                                        @endif
                                                        @if(isset($selectedDetails['payment']['manual']) && $selectedDetails['payment']['manual'])
                                                            <div class="mb-2"><span class="badge border border-dark text-dark px-2 py-1"><i class="bi bi-cash me-1"></i> Manual Transfer</span></div>
                                                            @php
                                                                $receiptFile = $selectedDetails['payment']['receipt_path'] ?? $selectedDetails['payment']['file_path'] ?? null;
                                                            @endphp
                                                            @if($receiptFile)
                                                                <a href="{{ asset('storage/' . $receiptFile) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                                                    <i class="bi bi-file-earmark-image"></i> View Receipt
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDetails">Close</button>
                    @if(isset($current) && $current->status === 'pending')
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
