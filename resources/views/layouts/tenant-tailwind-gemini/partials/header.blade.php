@php
    $branches = $__branches ?? collect();
    $currentBranch = admin()?->branch_id;
    $currentBranchName = __('general.layout.all_branches');
    $activeBranchObj = $branches->where('id', $currentBranch)->first() ?? null;

    if ($activeBranchObj) {
        $currentBranchName = $activeBranchObj->name;
    }

    $notificationClassMap = [
        'd-flex align-items-center py-10px dropdown-item text-wrap fw-semibold' => 'unread-notification flex items-start gap-3 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-700/70',
        'fs-20px' => 'flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 text-base text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
        'text-theme' => 'text-blue-600 dark:text-blue-400',
        'flex-1 flex-wrap ps-3' => 'min-w-0 flex-1 ltr:pl-1 rtl:pr-1',
        'mb-1 text-inverse' => 'mb-1 text-sm font-medium text-gray-800 dark:text-gray-100',
        'small text-inverse text-opacity-50' => 'text-xs text-gray-500 dark:text-gray-400',
        'ps-2 fs-16px' => 'flex-shrink-0 text-sm text-gray-400 dark:text-gray-500',
    ];

    $renderNotification = static function ($notification) use ($notificationClassMap) {
        $html = __(
            $notification->data['translation_key'],
            ($notification->data['translation_params'] ?? []) + [
                'id' => $notification->id,
                'date' => carbon($notification->created_at)->diffForHumans(),
            ]
        );

        return strtr($html, $notificationClassMap);
    };
@endphp

<header class="z-40 flex h-16 items-center justify-between gap-4 border-b border-gray-200 bg-white px-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:px-6">
    <div class="flex min-w-0 items-center gap-4">
        @if(!isset($withoutSidebar))
            <button type="button" class="rounded-md p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700" @click="sidebarOpen = !sidebarOpen">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
            </button>
        @endif

        <div class="min-w-0">
            <div class="truncate text-lg font-semibold text-gray-800 dark:text-white">{{ $title ?? admin()?->name ?? tenantSetting('business_name', tenant()->name) }}</div>
        </div>
    </div>

    <div class="flex items-center gap-2 lg:gap-3">

        @if($branches->count() > 0)
            <div class="relative hidden md:block" x-data="{ open: false }" @click.away="open = false">
                <button type="button" @click="open = !open" class="flex items-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fa fa-building text-gray-400"></i>
                    <span class="max-w-40 truncate">{{ $currentBranchName }}</span>
                    <i class="fa fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition x-cloak class="absolute right-0 rtl:left-0 rtl:right-auto mt-2 w-56 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800 z-50">
                    <div class="border-b border-gray-100 px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 dark:border-gray-700">{{ __('general.layout.all_branches') }}</div>
                    <div class="max-h-60 overflow-y-auto p-2">
                        <a href="{{ url('/admin/switch-branch/') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition {{ empty($currentBranch) ? 'bg-blue-50 font-semibold text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                            <i class="fa fa-th-large text-xs"></i>
                            <span>{{ __('general.layout.all_branches') }}</span>
                            @if(empty($currentBranch))
                                <i class="fa fa-check ms-auto ltr:ml-auto rtl:mr-auto"></i>
                            @endif
                        </a>

                        @foreach($branches as $b)
                            <a href="{{ url('/admin/switch-branch/' . $b->id) }}" class="mt-1 flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition {{ $currentBranch == $b->id ? 'bg-blue-50 font-semibold text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                <i class="fa fa-building-o text-xs"></i>
                                <span class="truncate">{{ $b->name }}</span>
                                @if($currentBranch == $b->id)
                                    <i class="fa fa-check ms-auto ltr:ml-auto rtl:mr-auto"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- <button type="button" @click="rtl = !rtl" class="rounded-md border border-gray-300 px-3 py-1 text-xs font-bold transition hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">
            <span x-text="rtl ? 'LTR' : 'RTL'"></span>
        </button> --}}

        <button type="button" @click="darkMode = !darkMode" class="rounded-full p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
            <svg x-show="!darkMode" x-cloak class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
            <svg x-show="darkMode" x-cloak class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM4.343 4.343A1 1 0 105.757 2.93l-.707-.707A1 1 0 103.636 3.636l.707.707zm-1.414 9.9l-.707.707a1 1 0 101.414 1.414l.707-.707a1 1 0 10-1.414-1.414zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-8-6a1 1 0 100 2H3a1 1 0 100-2H2zm15 0a1 1 0 100 2h1a1 1 0 100-2h-1z" />
            </svg>
        </button>

        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button type="button" @click="open = !open" class="relative rounded-full p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                @if(isset($__unreaded_notifications) && count($__unreaded_notifications) > 0)
                    <span class="absolute right-2 top-2 h-2 w-2 rounded-full border-2 border-white bg-red-500 dark:border-gray-800"></span>
                @endif
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <div x-show="open" x-transition x-cloak class="absolute right-0 rtl:left-0 rtl:right-auto mt-2 w-80 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800 z-50">
                <div class="border-b border-gray-100 p-3 text-sm font-bold dark:border-gray-700">{{ __('general.layout.notifications') }}</div>
                <div class="max-h-80 overflow-y-auto">
                    @forelse (($__unreaded_notifications ?? []) as $notification)
                        <div class="border-b border-gray-100 last:border-0 dark:border-gray-700">
                            {!! $renderNotification($notification) !!}
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('general.layout.no_new_notifications') }}</div>
                    @endforelse
                </div>
                <div class="sticky bottom-0 border-t border-gray-100 bg-white p-3 text-center dark:border-gray-700 dark:bg-gray-800">
                    <a href="{{ route('admin.notifications.list') }}" class="text-sm font-semibold text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">{{ __('general.layout.see_all') }}</a>
                </div>
            </div>
        </div>

        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button type="button" @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-blue-500 bg-blue-600 text-sm font-bold text-white">{{ strtoupper(substr(admin()?->name ?? 'A', 0, 1)) }}</div>
            </button>
            <div x-show="open" x-transition x-cloak class="absolute right-0 rtl:left-0 rtl:right-auto mt-2 w-48 rounded-xl border border-gray-200 bg-white py-2 shadow-lg dark:border-gray-700 dark:bg-gray-800 z-50">
                <div class="px-4 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-gray-400 dark:text-gray-500">{{ __('general.pages.admins.name') }}</div>
                <div class="px-4 pb-3 text-sm font-semibold text-gray-800 dark:text-gray-100">{{ admin()?->name }}</div>
                <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                <a href="{{ route('admin.logout') }}" class="flex items-center px-4 py-2 text-sm font-semibold text-red-500 transition hover:bg-gray-50 dark:hover:bg-gray-700">
                    <span>{{ __('general.layout.logout') }}</span>
                    <i class="fa fa-toggle-off ltr:ml-auto rtl:mr-auto"></i>
                </a>
            </div>
        </div>
    </div>
</header>
