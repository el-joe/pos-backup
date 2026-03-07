<!DOCTYPE html>
<html lang="{{ $__locale }}" dir="{{ $__locale != 'ar' ? 'ltr' : 'rtl' }}" x-data="geminiLayout()" :class="{ 'dark': darkMode }" x-cloak>
<head>
    <meta charset="utf-8">
    <title>{{ tenantSetting('business_name', tenant()->name) }} | {{ $title ?? '' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon_io/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon_io/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/favicon_io/android-chrome-512x512.png">
    <link rel="manifest" href="/favicon_io/site.webmanifest">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#2563eb',
                            600: '#1d4ed8',
                            700: '#1e40af'
                        }
                    }
                }
            }
        };
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    @include('layouts.hud.partials.styles')
    <style>
        [x-cloak] { display: none !important; }
        .gemini-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .gemini-scroll::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.65); border-radius: 999px; }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-slate-100 text-slate-800 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen {{ isset($withoutSidebar) ? '' : 'lg:flex' }}">
        @if(!isset($withoutSidebar))
            @include('layouts.tenant-tailwind-gemini.partials.sidebar')
        @endif

        <div class="flex min-h-screen flex-1 flex-col overflow-hidden">
            @include('layouts.tenant-tailwind-gemini.partials.header')

            <main class="flex-1 overflow-y-auto gemini-scroll px-4 py-4 lg:px-8 lg:py-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @include('layouts.hud.partials.scripts')
    @livewireScripts
    @stack('scripts')
    @livewire('operations')

    <script>
        function geminiLayout() {
            return {
                darkMode: localStorage.getItem('gemini-dark-mode') === '1',
                sidebarOpen: window.innerWidth >= 1024,
                init() {
                    document.documentElement.classList.toggle('dark', this.darkMode);
                    this.$watch('darkMode', value => {
                        document.documentElement.classList.toggle('dark', value);
                        localStorage.setItem('gemini-dark-mode', value ? '1' : '0');
                    });
                }
            }
        }

        window.addEventListener('download-file', event => {
            window.open(event.detail[0].url, '_blank');
        });

        @if(session()->has('success'))
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                position: 'center',
            });
        @endif

        @if(session()->has('error'))
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000,
                position: 'center',
            });
        @endif

        function markAsRead(event,id) {
            const element = $(event.currentTarget);
            const href = element.data('href');
            fetch('/admin/notifications/mark-as-read/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    element.removeClass('unread-notification').addClass('read-notification');
                    if(href) {
                        window.location.href = href;
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const currentUrl = new URL(window.location.href);
            const panel = currentUrl.searchParams.get('panel');

            if (!panel) {
                return;
            }

            document.querySelectorAll('a[href]').forEach(anchor => {
                const href = anchor.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                    return;
                }

                try {
                    const url = new URL(href, window.location.origin);
                    if (url.origin !== window.location.origin) {
                        return;
                    }

                    if (!url.searchParams.has('panel')) {
                        url.searchParams.set('panel', panel);
                        anchor.setAttribute('href', url.pathname + url.search + url.hash);
                    }
                } catch (e) {
                }
            });
        });
    </script>
</body>
</html>
