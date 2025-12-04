<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>mohaaseb.com - Complete ERP & POS Solution for Businesses</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/favicon_io/site.webmanifest">

	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="mohaaseb.com provides a comprehensive ERP & POS system to manage your business efficiently. Streamline sales, inventory, purchases, accounting, and reporting with ease.">
    <meta name="keywords" content="mohaaseb.com, ERP software, POS system, business management, inventory management, accounting software, sales management, purchase management, reporting software">
    <meta name="author" content="mohaaseb.com">

    <meta property="og:title" content="mohaaseb.com - Complete ERP & POS Solution">
    <meta property="og:description" content="Manage your business smarter with mohaaseb.com ERP & POS. Handle sales, inventory, purchases, and accounting with ease.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://mohaaseb.com/">
    <meta property="og:image" content="https://mohaaseb.com/images/erp-pos-og-image.png">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="mohaaseb.com - Complete ERP & POS Solution">
    <meta name="twitter:description" content="mohaaseb.com helps businesses manage sales, inventory, purchases, and accounting efficiently.">
    <meta name="twitter:image" content="https://mohaaseb.com/images/erp-pos-twitter-image.png">

    <!-- Robots -->
    <meta name="robots" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://mohaaseb.com/">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">


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
