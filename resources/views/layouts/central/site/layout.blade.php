<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="dark" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}">
<head>
	<meta charset="utf-8">
    {{-- <title>@yield('title', __('website.titles.main'))</title> --}}

    @isset($seoData)
    {!! seo($seoData) !!}
    @endisset
    <meta name="keywords" content="mohaaseb.com, ERP software,mohaseb erp system, POS system, business management, inventory management, accounting software, sales management, purchase management, reporting software,erp,enterprise resource management software, enterprise resource planning software, enterprise resource planning software, enterprise resource software, erp enterprise resource planning software, erp software, erp system">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="icon" href="{{ asset('favicon_io/favicon.ico') }}" type="image/x-icon">
    <link rel="manifest" href="{{ asset('favicon_io/site.webmanifest') }}">

	<meta name="viewport" content="width=device-width, initial-scale=1">


    @include('layouts.central.site.partials.styles')
    @stack('styles')
</head>
<body>
	<!-- BEGIN #app -->
	<div id="app" class="app">
        @include('layouts.central.site.partials.header')

        @yield('content')

        @include('layouts.central.site.partials.footer')
	</div>
	<!-- END #app -->

    @include('layouts.central.site.partials.scripts')
    @stack('scripts')
</body>
</html>
