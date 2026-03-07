<aside
    class="border-b border-slate-200 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90 lg:flex lg:w-72 lg:flex-col lg:border-b-0 lg:border-e"
    :class="sidebarOpen ? 'block' : 'hidden lg:flex'">
    <div class="flex h-16 items-center justify-between border-b border-slate-200 px-5 dark:border-slate-800">
        <a href="{{ panelAwareUrl(route('admin.statistics')) }}" class="flex items-center gap-3">
            <img src="{{ tenantSetting('logo', asset('mohaaseb_en_dark.png')) }}" alt="{{ tenantSetting('business_name', tenant()->name) }}" class="h-10 w-10 rounded-xl object-contain bg-slate-100 p-1 dark:bg-slate-800">
            <div>
                <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ tenantSetting('business_name', tenant()->name) }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('general.layout.navigation') }}</div>
            </div>
        </a>

        <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 lg:hidden" @click="sidebarOpen = false">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <div class="gemini-scroll flex-1 overflow-y-auto px-3 py-4">
        <div class="space-y-1">
            @foreach (config('sidebar-links') as $sidebarData)
                {!! sidebarGemini($sidebarData) !!}
            @endforeach
        </div>
    </div>
</aside>
