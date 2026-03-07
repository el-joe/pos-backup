<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
    <div class="flex h-16 items-center justify-between gap-3 px-4 lg:px-8">
        <div class="flex items-center gap-3">
            @if(!isset($withoutSidebar))
                <button type="button" class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 lg:hidden" @click="sidebarOpen = true">
                    <i class="fa fa-bars"></i>
                </button>
            @endif

            <div>
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.layout.welcome') }}</div>
                <div class="text-lg font-semibold text-slate-900 dark:text-white">{{ admin()?->name ?? tenantSetting('business_name', tenant()->name) }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2 lg:gap-3">
            @if(isset($__branches) && count($__branches) > 0)
                @php
                    $currentBranch = admin()?->branch_id;
                    $currentBranchName = __('general.layout.all_branches');
                    $activeBranchObj = $__branches->where('id', $currentBranch)->first();
                    if($activeBranchObj) {
                        $currentBranchName = $activeBranchObj->name;
                    }
                @endphp
                <div class="hidden md:block">
                    <select onchange="window.location.href = this.value" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-brand-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <option value="{{ panelAwareUrl(url('/admin/switch-branch/')) }}">{{ __('general.layout.all_branches') }}</option>
                        @foreach($__branches as $b)
                            <option value="{{ panelAwareUrl(url('/admin/switch-branch/' . $b->id)) }}" @selected($currentBranch == $b->id)>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <a href="{{ request()->fullUrlWithQuery(['panel' => defaultLayout() === 'tenant-tailwind-gemini' ? 'hud' : 'tenant-tailwind-gemini']) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                <i class="fa {{ defaultLayout() === 'tenant-tailwind-gemini' ? 'fa-desktop' : 'fa-wand-magic-sparkles' }}"></i>
                <span class="hidden sm:inline">{{ defaultLayout() === 'tenant-tailwind-gemini' ? 'HUD' : 'Gemini' }}</span>
            </a>

            <button type="button" @click="darkMode = !darkMode" class="rounded-xl border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                <i class="fa" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
            </button>

            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button type="button" @click="open = !open" class="relative rounded-xl border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    <i class="fa fa-bell"></i>
                    @if(isset($__unreaded_notifications) && count($__unreaded_notifications) > 0)
                        <span class="absolute -right-1 -top-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">{{ count($__unreaded_notifications) }}</span>
                    @endif
                </button>
                <div x-show="open" x-transition x-cloak class="absolute {{ $__locale == 'en' ? 'right-0' : 'left-0' }} mt-2 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                    <div class="border-b border-slate-100 px-4 py-3 text-sm font-semibold dark:border-slate-800">{{ __('general.layout.notifications') }}</div>
                    <div class="max-h-80 overflow-y-auto">
                        @forelse ($__unreaded_notifications ?? [] as $notification)
                            <div class="border-b border-slate-100 px-4 py-3 text-sm last:border-0 dark:border-slate-800">
                                {!! __($notification->data['translation_key'], $notification->data['translation_params']+['id'=>$notification->id,'date'=>carbon($notification->created_at)->diffForHumans()] ?? []) !!}
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-slate-500">{{ __('general.layout.no_new_notifications') }}</div>
                        @endforelse
                    </div>
                    <div class="border-t border-slate-100 px-4 py-3 text-center dark:border-slate-800">
                        <a href="{{ panelAwareUrl(route('admin.notifications.list')) }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">{{ __('general.layout.see_all') }}</a>
                    </div>
                </div>
            </div>

            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button type="button" @click="open = !open" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">{{ strtoupper(substr(admin()?->name ?? 'A', 0, 1)) }}</div>
                    <div class="hidden text-start sm:block">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ admin()?->name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.name') }}</div>
                    </div>
                </button>
                <div x-show="open" x-transition x-cloak class="absolute {{ $__locale == 'en' ? 'right-0' : 'left-0' }} mt-2 w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                    <a href="{{ panelAwareUrl(route('admin.statistics')) }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('general.layout.dashboard') }}</a>
                    <a href="{{ panelAwareUrl(route('admin.notifications.list')) }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('general.layout.notifications') }}</a>
                    <div class="my-2 border-t border-slate-100 dark:border-slate-800"></div>
                    <a href="{{ panelAwareUrl(route('admin.logout')) }}" class="block rounded-xl px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:hover:bg-rose-500/10">{{ __('general.layout.logout') }}</a>
                </div>
            </div>
        </div>
    </div>
</header>
