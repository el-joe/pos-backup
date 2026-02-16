<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('general.layout.notifications') }}</h5>
        </div>

        <div class="card-body">
            <!-- Single Notification Example (Same Style You Sent) -->
            @foreach ($notifications as $notification)
                <div class="border rounded p-2 mb-2 {{ $notification->read_at ? 'read-notification' : 'unread-notification' }}">
                    {!! __($notification->data['translation_key'], $notification->data['translation_params']+['id'=>$notification->id,'date'=>carbon($notification->created_at)->diffForHumans()] ?? []) !!}
                </div>
                @endforeach

        </div>

        <!-- CARD ARROWS (REQUIRED FOR YOUR DESIGN) -->
        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* ===== Unread Notification ===== */
        html[data-bs-theme="light"] .unread-notification {
            background-color: #f8f9fa; /* Light mode */
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600; /* bold for unread */
            position: relative;
            color: #212529; /* text dark */
        }

        html[data-bs-theme="light"].unread-notification .badge {
            width: 10px;
            height: 10px;
            display: inline-block;
            position: absolute;
            top: 12px;
            left: 12px;
            background-color: #0d6efd; /* blue dot */
        }

        /* ===== Read Notification ===== */
        html[data-bs-theme="light"] .read-notification {
            background-color: #ffffff; /* Light mode */
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 400;
            color: #6c757d; /* muted text */
        }

        /* ===== Dark Mode Support ===== */
        html[data-bs-theme="dark"] .unread-notification {
            background-color: #2c2f33; /* dark gray */
            border-color: #444;
            color: #f8f9fa;
        }

        html[data-bs-theme="dark"] .unread-notification .badge {
            background-color: #0d6efd; /* keep blue dot */
        }

        html[data-bs-theme="dark"] .read-notification {
            background-color: #1e2125; /* darker background */
            border-color: #444;
            color: #adb5bd; /* lighter muted text */
        }

    </style>
@endpush
