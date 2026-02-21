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
	<div id="app" class="app">
		@include('layouts.employee.partials.header')
		@include('layouts.employee.partials.sidebar')

		<div id="content" class="app-content">
			{{ $slot }}
		</div>

		@include('layouts.hud.partials.theme-panel')
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
	</div>

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
	</script>
</body>
</html>
