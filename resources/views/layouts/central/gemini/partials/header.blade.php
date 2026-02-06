<nav id="navbar" class="fixed w-full z-50 transition-all duration-300 py-4 bg-white/80 backdrop-blur-md dark:bg-slate-900/90 dark:border-b dark:border-slate-800">
    <div class="container mx-auto px-6 flex justify-between items-center">

        <a href="#" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20 text-white transform group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <span class="text-2xl font-extrabold text-slate-800 tracking-tight group-hover:text-brand-600 transition-colors dark:text-white">
                Mohaaseb<span class="text-brand-500">.</span>
            </span>
        </a>

        <div class="hidden lg:flex items-center space-x-8 font-semibold text-slate-600 dark:text-slate-300">
            <a href="#about" class="hover:text-brand-500 transition-colors">About</a>
            <a href="#features" class="hover:text-brand-500 transition-colors">Features</a>
            <a href="#testimonials" class="hover:text-brand-500 transition-colors">Clients</a>
            <a href="#blog" class="hover:text-brand-500 transition-colors">Insights</a>
        </div>

        <div class="hidden lg:flex items-center gap-3">
            <div class="relative group">
                <button class="flex items-center gap-2 px-3 py-2 rounded-full border border-slate-200 bg-white hover:border-brand-500 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                    <img src="https://flagcdn.com/w40/us.png" alt="English" class="w-5 h-5 rounded-full object-cover">
                    <span class="text-sm font-bold">English</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 group-hover:rotate-180 transition-transform"></i>
                </button>
                <div class="absolute top-full right-0 mt-2 w-32 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 hidden group-hover:block overflow-hidden p-1">
                    <a href="index_ar.html" class="flex items-center gap-3 px-3 py-2 hover:bg-brand-50 dark:hover:bg-slate-700 rounded-lg transition group/item">
                        <img src="https://flagcdn.com/w40/sa.png" alt="Arabic" class="w-5 h-5 rounded-full object-cover shadow-sm">
                        <span class="text-sm font-semibold text-slate-600 dark:text-slate-300 group-hover/item:text-brand-600">العربية</span>
                    </a>
                </div>
            </div>

            <button id="theme-toggle" class="w-10 h-10 rounded-full border border-slate-200 bg-white hover:border-brand-500 hover:text-brand-500 transition flex items-center justify-center text-slate-600 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-yellow-400">
                <i class="fa-regular fa-moon text-lg"></i>
            </button>

            <a href="checkout.html" class="px-6 py-2.5 bg-brand-500 text-white rounded-full font-bold shadow-lg shadow-brand-500/30 hover:bg-brand-600 transition-all transform hover:-translate-y-0.5 ml-2">
                Get Started
            </a>
        </div>

        <button id="mobile-menu-btn" class="lg:hidden text-2xl text-slate-800 dark:text-white">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden bg-white dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800 absolute w-full top-full left-0 shadow-xl p-6 flex flex-col gap-4">
        <a href="#about" class="font-medium hover:text-brand-500 dark:text-slate-300">About</a>
        <a href="#features" class="font-medium hover:text-brand-500 dark:text-slate-300">Features</a>
        <a href="#testimonials" class="font-medium hover:text-brand-500 dark:text-slate-300">Clients</a>

        <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-4 mt-2">
            <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Language</span>
            <a href="index_ar.html" class="flex items-center gap-2 px-3 py-1 bg-slate-50 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700">
                <img src="https://flagcdn.com/w40/sa.png" class="w-5 h-5 rounded-full"> <span class="text-sm font-bold dark:text-white">العربية</span>
            </a>
        </div>

        <div class="flex items-center justify-between">
            <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Appearance</span>
            <button id="mobile-theme-toggle" class="flex items-center gap-2 px-3 py-1 bg-slate-50 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-yellow-400">
               <i class="fa-regular fa-moon"></i> <span class="text-sm font-bold dark:text-white">Switch Mode</span>
            </button>
        </div>

        <a href="checkout.html" class="block text-center w-full px-6 py-3 bg-brand-500 text-white rounded-lg font-bold">Get Started</a>
    </div>
</nav>
