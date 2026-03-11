<div class="space-y-4">
    <x-tenant-tailwind-gemini.table-card :title="__('general.layout.notifications')" icon="fa-bell" :description="trans_choice('general.layout.notifications', count($notifications ?? []))">
        <div class="space-y-3 p-5">
            @forelse ($notifications as $notification)
                <article class="gemini-notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}">
                    <div class="gemini-notification-marker"></div>
                    <div class="gemini-notification-body">
                        <div class="gemini-notification-time">{{ carbon($notification->created_at)->diffForHumans() }}</div>
                        <div class="gemini-notification-content">
                            {!! __($notification->data['translation_key'], $notification->data['translation_params'] + ['id' => $notification->id, 'date' => carbon($notification->created_at)->diffForHumans()] ?? []) !!}
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 px-5 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                    {{ __('general.layout.notifications') }}
                </div>
            @endforelse
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('styles')
    <style>
        .gemini-notification-item {
            position: relative;
            display: flex;
            gap: 1rem;
            border: 1px solid rgb(226 232 240 / 1);
            border-radius: 1.5rem;
            padding: 1rem 1.1rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.95) 100%);
        }

        .gemini-notification-item.is-unread {
            border-color: rgb(191 219 254 / 1);
            background: linear-gradient(180deg, rgba(239,246,255,0.98) 0%, rgba(248,250,252,0.98) 100%);
        }

        .gemini-notification-marker {
            width: 0.7rem;
            height: 0.7rem;
            margin-top: 0.45rem;
            flex-shrink: 0;
            border-radius: 999px;
            background: rgb(148 163 184 / 1);
            box-shadow: 0 0 0 6px rgb(148 163 184 / 0.12);
        }

        .gemini-notification-item.is-unread .gemini-notification-marker {
            background: rgb(37 99 235 / 1);
            box-shadow: 0 0 0 6px rgb(37 99 235 / 0.14);
        }

        .gemini-notification-body {
            min-width: 0;
            flex: 1;
        }

        .gemini-notification-time {
            margin-bottom: 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #64748b;
        }

        .gemini-notification-content {
            color: #0f172a;
        }

        .gemini-notification-content a {
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
        }

        .gemini-notification-content a:hover {
            text-decoration: underline;
        }

        .dark .gemini-notification-item {
            border-color: rgb(51 65 85 / 1);
            background: linear-gradient(180deg, rgba(15,23,42,0.96) 0%, rgba(2,6,23,0.98) 100%);
        }

        .dark .gemini-notification-item.is-unread {
            border-color: rgb(59 130 246 / 0.35);
            background: linear-gradient(180deg, rgba(30,41,59,0.98) 0%, rgba(2,6,23,0.98) 100%);
        }

        .dark .gemini-notification-time {
            color: #94a3b8;
        }

        .dark .gemini-notification-content {
            color: #e2e8f0;
        }
    </style>
@endpush
