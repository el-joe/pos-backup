<!DOCTYPE html>
<html lang="{{ $__locale }}" dir="{{ $__locale != 'ar' ? 'ltr' : 'rtl' }}" data-bs-theme="dark">
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

    @include('layouts.hud.partials.styles')
    @stack('styles')
    @livewireStyles
</head>
<body>
	<!-- BEGIN #app -->
	<div id="app" class="app {{ isset($withoutSidebar) ? 'app-content-full-height app-without-sidebar app-without-header' : ''  }}">
        @if(!isset($withoutSidebar))
        @include('layouts.hud.partials.header')
        @include('layouts.hud.partials.sidebar')
        @endif
		<!-- BEGIN #content -->
		<div id="content" class="app-content {{ isset($withoutSidebar) ? 'p-1 ps-xl-4 pe-xl-4 pt-xl-3 pb-xl-3' : '' }}">
            {{ $slot }}
		</div>
		<!-- END #content -->
        @include('layouts.hud.partials.theme-panel')
		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
		<!-- END btn-scroll-top -->
	</div>
	<!-- END #app -->
    @include('layouts.hud.partials.scripts')
    @livewireScripts
    @stack('scripts')
    @livewire('operations')

    <script>
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

        // $(document).ready(function() {

        //     $('.changeDirection').on('click', function(e) {
        //         e.preventDefault();
        //         var lang = $(this).data('direction') === 'ltr' ? 'en' : 'ar';
        //         changeLanguage(lang);
        //     });

        //     function changeLanguage(lang) {
        //         fetch(`/change-language/${lang}`, {
        //             method: 'GET',
        //             headers: {
        //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //             }
        //         })
        //         .then(response => {
        //             if (response.ok) {
        //                 location.reload();
        //             } else {
        //                 console.error('Failed to change language');
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //         });
        //     }


        // });
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
                } else {
                    console.error('Failed to mark notification as read');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
