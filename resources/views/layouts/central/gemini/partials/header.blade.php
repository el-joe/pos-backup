@php($lang = $__currentLang ?? app()->getLocale())

<nav id="navbar" class="fixed w-full z-50 transition-all duration-300 py-4 bg-white/80 backdrop-blur-md dark:bg-slate-900/90 dark:border-b dark:border-slate-800">
    <div class="container mx-auto px-6 flex justify-between items-center">

        <a href="/" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20 text-white transform group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <span class="text-2xl font-extrabold text-slate-800 tracking-tight group-hover:text-brand-600 transition-colors dark:text-white">
                @if(app()->getLocale() == 'ar')
                محاسب<span class="text-brand-500">.</span>
                @else
                Mohaaseb<span class="text-brand-500">.</span>
                @endif
            </span>
        </a>

        <div class="hidden lg:flex items-center space-x-8 font-semibold text-slate-600 dark:text-slate-300">
            <a href="/" class="hover:text-brand-500 transition-colors {{ app()->getLocale() == 'ar' ? 'px-8' : '' }}">{{ __('gemini-landing.nav.home') }}</a>
            <a href="{{ route('blogs.index') }}" class="hover:text-brand-500 transition-colors">{{ __('gemini-landing.nav.blogs') }}</a>
            <a href="{{ route('pricing') }}" class="hover:text-brand-500 transition-colors">{{ __('gemini-landing.nav.pricing') }}</a>
            <a href="{{ route('contact') }}" class="hover:text-brand-500 transition-colors">{{ __('gemini-landing.nav.contact') }}</a>
        </div>

        <div class="hidden lg:flex items-center gap-3">
            <div class="relative">
                <?php
                    $languages = [
                        'en' => [
                            'image'=> 'https://flagcdn.com/w40/us.png',
                            'code' => 'en',
                            'name' => 'English',
                        ],
                        'ar' => [
                            'image'=> 'https://flagcdn.com/w40/sa.png',
                            'code' => 'ar',
                            'name' => 'العربية',
                        ]
                    ];

                    $currentLang = $languages[app()->getLocale()] ?? $languages['en'];
                    $anotherLang = app()->getLocale() == 'en' ? $languages['ar'] : $languages['en'];
                ?>
                <button id="lang-menu-btn" class="flex items-center gap-2 px-3 py-2 rounded-full border border-slate-200 bg-white hover:border-brand-500 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                    <img src="{{ $currentLang['image'] }}" alt="{{ $currentLang['name'] }}" class="w-5 h-5 rounded-full object-cover">
                    <span class="text-sm font-bold">{{ $currentLang['name'] }}</span>
                    <i id="lang-chevron" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"></i>
                </button>

                <div id="lang-dropdown" class="absolute top-full right-0 mt-2 w-32 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 hidden overflow-hidden p-1 z-50">
                    @foreach(array_filter($languages, fn($lang) => $lang['code'] !== app()->getLocale()) as $lang)
                    <a href="{{ route('site.lang', $lang['code']) }}" class="flex items-center gap-2 px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition group/item">
                        <img src="{{ $lang['image'] }}" alt="{{ $lang['name'] }}" class="w-5 h-5 rounded-full object-cover shadow-sm">
                        <span class="text-sm font-semibold text-slate-600 dark:text-slate-300 group-hover/item:text-brand-600">{{ $lang['name'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            <button id="theme-toggle" class="w-10 h-10 rounded-full border border-slate-200 bg-white hover:border-brand-500 hover:text-brand-500 transition flex items-center justify-center text-slate-600 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-yellow-400">
                <i class="fa-regular fa-moon text-lg"></i>
            </button>

            <a href="{{ route('pricing') }}" class="px-6 py-2.5 bg-brand-500 text-white rounded-full font-bold shadow-lg shadow-brand-500/30 hover:bg-brand-600 transition-all transform hover:-translate-y-0.5 ml-2">
                {{ __('gemini-landing.nav.get_started') }}
            </a>
        </div>

        <button id="mobile-menu-btn" class="lg:hidden text-2xl text-slate-800 dark:text-white">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden bg-white dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800 absolute w-full top-full left-0 shadow-xl p-6 space-y-4">
        <a href="/" class="font-medium hover:text-brand-500 dark:text-slate-300">{{ __('gemini-landing.nav.home') }}</a>
        <a href="{{ route('blogs.index') }}" class="font-medium hover:text-brand-500 dark:text-slate-300">{{ __('gemini-landing.nav.blogs') }}</a>
        <a href="{{ route('pricing') }}" class="font-medium hover:text-brand-500 dark:text-slate-300">{{ __('gemini-landing.nav.pricing') }}</a>
        <a href="{{ route('contact') }}" class="font-medium hover:text-brand-500 dark:text-slate-300">{{ __('gemini-landing.nav.contact') }}</a>

        <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-4 mt-2">
            <span class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('gemini-landing.nav.language') }}</span>
            <a href="{{ route('site.lang',$anotherLang['code']) }}" class="flex items-center gap-2 px-3 py-1 bg-slate-50 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700">
                <img src="{{ $anotherLang['image'] }}" class="w-5 h-5 rounded-full"> <span class="text-sm font-bold dark:text-white">{{ $anotherLang['name'] }}</span>
            </a>
        </div>

        <div class="flex items-center justify-between">
            <span class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('gemini-landing.nav.appearance') }}</span>
            <button id="mobile-theme-toggle" class="flex items-center gap-2 px-3 py-1 bg-slate-50 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-yellow-400">
                <i class="fa-regular fa-moon"></i> <span class="text-sm font-bold dark:text-white">{{ __('gemini-landing.nav.change_mode') }}</span>
            </button>
        </div>

        <a href="{{ route('pricing') }}" class="block text-center w-full px-6 py-3 bg-brand-500 text-white rounded-lg font-bold">{{ __('gemini-landing.nav.get_started') }}</a>
    </div>
</nav>
