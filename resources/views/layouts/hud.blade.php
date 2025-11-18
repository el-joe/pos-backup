<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>{{ tenant()?->name }} | {{ $title ?? '' }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

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

        $(document).ready(function() {

            $('.changeDirection').on('click', function(e) {
                e.preventDefault();
                var lang = $(this).data('theme-direction') === 'ltr' ? 'en' : 'ar';
                changeLanguage(lang);
            });

            function changeLanguage(lang) {
                fetch(`/change-language/${lang}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        console.error('Failed to change language');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });

    </script>

</body>
</html>
