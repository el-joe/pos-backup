<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <h5 class="mb-0">{{ __('general.pages.contacts.contacts') }}</h5>

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border">Total: {{ $stats['total'] }}</span>
                <span class="badge bg-warning text-dark">Unread: {{ $stats['unread'] }}</span>
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <button class="nav-link {{ $filter === 'all' ? 'active' : '' }}" wire:click="$set('filter', 'all')">All</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ $filter === 'unread' ? 'active' : '' }}" wire:click="$set('filter', 'unread')">Unread</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ $filter === 'read' ? 'active' : '' }}" wire:click="$set('filter', 'read')">Read</button>
                    </li>
                </ul>

                <div style="min-width: 260px;">
                    <input type="text" class="form-control" placeholder="Search by name or email..."
                        wire:model.live.debounce.300ms="search">
                </div>
            </div>

            @if (count($selectedIds) > 0)
                <div class="d-flex align-items-center gap-2 mb-3 p-2 bg-light border rounded">
                    <span class="fw-bold">{{ count($selectedIds) }} selected</span>
                    <button class="btn btn-sm btn-outline-success" wire:click="markRead">
                        <i class="bi bi-envelope-open"></i> Mark Read
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" wire:click="markUnread">
                        <i class="bi bi-envelope"></i> Mark Unread
                    </button>
                    <button class="btn btn-sm btn-danger" wire:click="deleteSelected"
                        wire:confirm="Are you sure you want to delete {{ count($selectedIds) }} contacts?">
                        <i class="bi bi-trash"></i> Delete Selected
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 32px;">
                                <input type="checkbox" wire:model.live="selectAll">
                            </th>
                            <th>{{ __('general.pages.contacts.name') }}</th>
                            <th>{{ __('general.pages.contacts.email') }}</th>
                            <th>{{ __('general.pages.contacts.phone') }}</th>
                            <th>{{ __('general.pages.contacts.message') }}</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr class="{{ is_null($contact->read_at) ? 'border-start border-warning border-3' : '' }}">
                                <td>
                                    <input type="checkbox" wire:model.live="selectedIds" value="{{ $contact->id }}">
                                </td>
                                <td class="{{ is_null($contact->read_at) ? 'fw-bold' : '' }}">{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone ?? '—' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($contact->message, 60) }}</td>
                                <td class="text-nowrap small text-muted">{{ $contact->created_at->translatedFormat('d M Y h:i A') }}</td>
                                <td>
                                    @if (is_null($contact->read_at))
                                        <span class="badge bg-warning text-dark">Unread</span>
                                    @else
                                        <span class="badge bg-success">Read</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button class="btn btn-sm btn-info text-white" wire:click="view({{ $contact->id }})" title="View">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $contact->id }})"
                                            wire:confirm="Are you sure you want to delete this contact?" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No contacts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $contacts->links("pagination::bootstrap-5") }}
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

    @if ($viewingId)
        @php $viewing = \App\Models\Contact::find($viewingId); @endphp
        @if ($viewing)
            <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Contact Message</h5>
                            <button type="button" class="btn-close" wire:click="$set('viewingId', null)" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-sm table-borderless mb-3">
                                <tbody>
                                    <tr><th class="text-muted" style="width: 100px;">Name</th><td>{{ $viewing->name }}</td></tr>
                                    <tr><th class="text-muted">Email</th><td>{{ $viewing->email }}</td></tr>
                                    <tr><th class="text-muted">Phone</th><td>{{ $viewing->phone ?? '—' }}</td></tr>
                                    <tr><th class="text-muted">Date</th><td>{{ $viewing->created_at->translatedFormat('d M Y h:i A') }}</td></tr>
                                </tbody>
                            </table>
                            <blockquote class="blockquote border-start border-3 ps-3">
                                {{ $viewing->message }}
                            </blockquote>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="markUnreadSingle({{ $viewing->id }})">
                                Mark Unread
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="$set('viewingId', null)">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
