<!DOCTYPE html>
<html lang="{{ $__locale }}" dir="{{ $__locale != 'ar' ? 'ltr' : 'rtl' }}" x-data="{ darkMode: localStorage.getItem('gemini-dark-mode') === '1', rtl: localStorage.getItem('gemini-rtl') === '1' || document.documentElement.getAttribute('dir') === 'rtl' }" :class="{ 'dark': darkMode }" :dir="rtl ? 'rtl' : 'ltr'" x-cloak>
<head>
    <meta charset="utf-8">
    <title>{{ tenantSetting('business_name', tenant()->name) }} | {{ $title ?? '' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon_io/favicon-16x16.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @include('layouts.tenant-tailwind-gemini.partials.styles')
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-slate-100 text-slate-800 dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-4 lg:px-8">
                <div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ tenantSetting('business_name', tenant()->name) }}</div>
                    <div class="text-lg font-semibold">{{ employee()?->name }}</div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium transition hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ panelAwareUrl(route('employee.dashboard')) }}">Dashboard</a>
                    <a class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium transition hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ panelAwareUrl(route('employee.profile')) }}">Profile</a>
                    <a class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium transition hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ panelAwareUrl(route('employee.payslips.list')) }}">Payslips</a>
                    <a class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium transition hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ panelAwareUrl(route('employee.leaves.list')) }}">Leaves</a>
                    <a class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium transition hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ panelAwareUrl(route('employee.claims.list')) }}">Claims</a>
                    <a class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium transition hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800" href="{{ panelAwareUrl(route('employee.attendance.list')) }}">Attendance</a>
                    <a class="rounded-xl bg-rose-500 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-600" href="{{ panelAwareUrl(route('employee.logout')) }}">Logout</a>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 lg:px-8">
            <div class="gemini-legacy-page">
                {{ $slot }}
            </div>
        </main>
    </div>

    @include('layouts.tenant-tailwind-gemini.partials.scripts')
    @livewireScripts
    @stack('scripts')
    @livewire('operations')

    <script>
        document.documentElement.classList.toggle('dark', localStorage.getItem('gemini-dark-mode') === '1');
        document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('gemini-dark-mode') === '1' ? 'dark' : 'light');
        document.documentElement.setAttribute('dir', localStorage.getItem('gemini-rtl') === '1' ? 'rtl' : document.documentElement.getAttribute('dir'));

        window.addEventListener('download-file', event => {
            window.open(event.detail[0].url, '_blank');
        });
    </script>
</body>
</html>
