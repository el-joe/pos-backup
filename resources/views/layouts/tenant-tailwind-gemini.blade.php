<!DOCTYPE html>
<html lang="{{ $__locale }}" dir="{{ $__locale != 'ar' ? 'ltr' : 'rtl' }}" x-data="geminiLayout()" :class="{ 'dark': darkMode }" :dir="rtl ? 'rtl' : 'ltr'" x-cloak>
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

    @include('layouts.tenant-tailwind-gemini.partials.styles')
    <style>
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-800 transition-colors duration-300 dark:bg-gray-900 dark:text-gray-200">
    <div class="{{ isset($withoutSidebar) ? 'min-h-screen flex flex-col' : 'flex h-screen overflow-hidden' }}">
        @if(!isset($withoutSidebar))
            <div x-show="sidebarOpen && !isDesktop" x-transition.opacity x-cloak class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden" @click="sidebarOpen = false"></div>
            @include('layouts.tenant-tailwind-gemini.partials.sidebar')
        @endif

        <div class="flex min-h-screen min-w-0 flex-1 flex-col overflow-hidden">
            @include('layouts.tenant-tailwind-gemini.partials.header')

            <main class="custom-scroll flex-1 overflow-y-auto bg-gray-100 p-4 dark:bg-gray-900 lg:p-8">
                <div class="gemini-legacy-page">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @include('layouts.tenant-tailwind-gemini.partials.scripts')
    @livewireScripts
    @stack('scripts')
    @livewire('operations')

    <script>
        function geminiLayout() {
            return {
                darkMode: localStorage.getItem('gemini-dark-mode') === '1',
                rtl: localStorage.getItem('gemini-rtl')
                    ? localStorage.getItem('gemini-rtl') === '1'
                    : document.documentElement.getAttribute('dir') === 'rtl',
                sidebarOpen: window.innerWidth >= 1024,
                isDesktop: window.innerWidth >= 1024,
                init() {
                    const syncViewport = () => {
                        const wasDesktop = this.isDesktop;
                        this.isDesktop = window.innerWidth >= 1024;

                        if (this.isDesktop && !wasDesktop) {
                            this.sidebarOpen = true;
                        }

                        if (!this.isDesktop && wasDesktop) {
                            this.sidebarOpen = false;
                        }
                    };

                    document.documentElement.classList.toggle('dark', this.darkMode);
                    document.documentElement.setAttribute('dir', this.rtl ? 'rtl' : 'ltr');
                    document.documentElement.setAttribute('data-bs-theme', this.darkMode ? 'dark' : 'light');

                    this.$watch('darkMode', value => {
                        document.documentElement.classList.toggle('dark', value);
                        document.documentElement.setAttribute('data-bs-theme', value ? 'dark' : 'light');
                        localStorage.setItem('gemini-dark-mode', value ? '1' : '0');
                    });

                    this.$watch('rtl', value => {
                        document.documentElement.setAttribute('dir', value ? 'rtl' : 'ltr');
                        localStorage.setItem('gemini-rtl', value ? '1' : '0');
                    });

                    window.addEventListener('resize', syncViewport);
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

        document.addEventListener('DOMContentLoaded', () => window.geminiUi?.boot());
    </script>
</body>
</html>
