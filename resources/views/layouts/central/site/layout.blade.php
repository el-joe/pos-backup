<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="dark" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}">
<head>
	<meta charset="utf-8">
    {{-- <title>@yield('title', __('website.titles.main'))</title> --}}

    @isset($seoData)
    {!! seo($seoData) !!}
    @endisset
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

    <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Mohaaseb ERP",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "5",
            "reviewCount": "1200"
        }
        }
</script>
</body>
</html>
