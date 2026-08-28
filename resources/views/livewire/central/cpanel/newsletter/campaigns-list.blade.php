<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.newsletter-campaigns') }} ({{ $campaigns->total() }})</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" href="{{ route('cpanel.newsletter.campaigns.create') }}">
                    <i class="fa fa-plus"></i> New Campaign
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Subject (EN)</th>
                            <th>Recipients</th>
                            <th>Status</th>
                            <th>Sent Count</th>
                            <th>Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->id }}</td>
                                <td>{{ $campaign->subject_en }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $campaign->recipient_type)) }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'secondary',
                                            'queued' => 'info',
                                            'sending' => 'warning',
                                            'sent' => 'success',
                                            'failed' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$campaign->status] ?? 'secondary' }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td>{{ $campaign->sent_count }}</td>
                                <td>{{ $campaign->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary me-1"
                                        href="{{ route('cpanel.newsletter.campaigns.edit', ['id' => $campaign->id]) }}"
                                        title="View / Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-secondary me-1" wire:click="duplicate({{ $campaign->id }})"
                                        title="Duplicate">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $campaign->id }})"
                                        title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No campaigns found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $campaigns->links("pagination::bootstrap-5") }}
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
