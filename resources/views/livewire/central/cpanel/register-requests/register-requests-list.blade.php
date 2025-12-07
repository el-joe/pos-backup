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
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($registerRequests as $req)
                            @php $d = $req->data; @endphp

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
