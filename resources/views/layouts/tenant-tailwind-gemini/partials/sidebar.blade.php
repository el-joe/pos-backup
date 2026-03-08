<aside
    class="fixed inset-y-0 z-50 flex w-64 flex-col border-e border-gray-200 bg-white transition-all duration-300 dark:border-gray-700 dark:bg-gray-800 lg:static lg:z-auto lg:flex"
    :class="sidebarOpen ? 'translate-x-0 lg:w-64' : '-translate-x-full lg:w-20 lg:translate-x-0'">
    <div class="flex h-16 items-center border-b border-gray-200 px-4 dark:border-gray-700" :class="sidebarOpen || !isDesktop ? 'justify-between' : 'justify-center'">
        <a href="{{ panelAwareUrl(route('admin.statistics')) }}" class="flex min-w-0 items-center gap-3">
            <img src="{{ tenantSetting('logo', asset('mohaaseb_en_dark.png')) }}" alt="{{ tenantSetting('business_name', tenant()->name) }}" class="h-9 w-9 flex-shrink-0 rounded-lg border border-gray-200 bg-white object-contain p-1 dark:border-gray-700 dark:bg-gray-900">
            <div x-show="sidebarOpen || !isDesktop" x-cloak class="min-w-0">
                <div class="truncate text-base font-bold tracking-tight text-gray-900 dark:text-white">{{ tenantSetting('business_name', tenant()->name) }}</div>
                <div class="text-xs uppercase tracking-[0.2em] text-gray-400">{{ __('general.layout.navigation') }}</div>
            </div>
        </a>

        <button type="button" class="rounded-md p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 lg:hidden" @click="sidebarOpen = false">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <nav class="custom-scroll flex-1 overflow-y-auto p-4 space-y-2">
        @foreach (config('sidebar-links') as $sidebarData)
            {!! view('layouts.tenant-tailwind-gemini.partials.sidebar-item', ['data' => $sidebarData, 'depth' => 0])->render() !!}
        @endforeach
    </nav>
</aside>
