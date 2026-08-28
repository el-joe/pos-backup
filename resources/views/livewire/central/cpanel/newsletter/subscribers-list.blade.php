<div class="col-12">
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Subscribers</div>
                    <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Active</div>
                    <div class="fs-3 fw-bold text-success">{{ $stats['active'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Unsubscribed This Month</div>
                    <div class="fs-3 fw-bold text-danger">{{ $stats['unsubscribed_this_month'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">{{ __('general.titles.newsletter-subscribers') }} ({{ $subscribers->total() }})</h5>
            <div class="d-flex align-items-center gap-2">
                <input type="text" wire:model.live.debounce.400ms="search" class="form-control form-control-sm"
                    placeholder="Search by email or name..." style="min-width: 240px;">
                <button class="btn btn-outline-theme" wire:click="exportCsv">
                    <i class="fa fa-file-export"></i> Export CSV
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Subscribed At</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscribers as $subscriber)
                            <tr>
                                <td>{{ $subscriber->id }}</td>
                                <td>{{ $subscriber->email }}</td>
                                <td>{{ $subscriber->name }}</td>
                                <td>{{ $subscriber->subscribed_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if ($subscriber->unsubscribed_at)
                                        <span class="badge bg-danger">Unsubscribed</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!$subscriber->unsubscribed_at)
                                        <button class="btn btn-sm btn-warning me-1"
                                            wire:click="unsubscribeAlert({{ $subscriber->id }})" title="Unsubscribe">
                                            <i class="fa fa-user-slash"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $subscriber->id }})"
                                        title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No subscribers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $subscribers->links("pagination::bootstrap-5") }}
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
