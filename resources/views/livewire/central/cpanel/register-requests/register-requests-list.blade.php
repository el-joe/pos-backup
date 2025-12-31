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
                                    @if ($req->status === 'pending')
                                        <div class="d-flex gap-1">
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
                                        </div>
                                    @endif
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
</div>
