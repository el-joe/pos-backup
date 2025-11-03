<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>HUD | {{ $title ?? '' }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

    @include('layouts.hud.partials.styles')
    @stack('styles')
    @livewireStyles
</head>
<body>
	<!-- BEGIN #app -->
	<div id="app" class="app">
        @include('layouts.hud.partials.header')
        @include('layouts.hud.partials.sidebar')
		<!-- BEGIN #content -->
		<div id="content" class="app-content">
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
</body>
</html>
