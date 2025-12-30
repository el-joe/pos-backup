<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Register Requests</h5>
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
                            <th>time</th>
                            <th>Read at</th>
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

                                <td>
                                    {{ $d['company']['name'] }}
                                </td>
                                <td>
                                    {{ $d['company']['email'] }}
                                </td>
                                <td>
                                    {{ $d['company']['phone'] }}
                                </td>
                                <td>
                                    {{ $req->created_at->translatedFormat('d M Y h:i A') }}
                                </td>
                                <td>
                                    @if ($req->read_at)
                                        <span class="text-muted" style="font-size: 0.9rem;">
                                            {{ \Carbon\Carbon::parse($req->read_at)->translatedFormat('d M Y h:i A') }}
                                        </span>
                                    @else
                                        <button class="btn btn-sm btn-outline-primary"
                                            wire:click="markAsRead({{ $req->id }})" wire:loading.attr="disabled"
                                            title="تحديد كمقروء">
                                            <i class="bi bi-eye"></i>
                                            <span wire:loading.remove
                                                wire:target="markAsRead({{ $req->id }})">مشاهدة</span>
                                            <span class="spinner-border spinner-border-sm" wire:loading
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
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="changeStatus({{ $req->id }}, 'rejected')">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success"
                                            wire:click="changeStatus({{ $req->id }}, 'approved')">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination center aligned (optional) --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $registerRequests->links() }}
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
