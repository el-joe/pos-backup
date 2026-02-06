<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    @isset($seoData)
    {!! seo($seoData) !!}
    @endisset

    <meta name="keywords" content="mohaaseb.com, ERP software,mohaseb erp system, POS system, business management, inventory management, accounting software, sales management, purchase management, reporting software,erp,enterprise resource management software, enterprise resource planning software, enterprise resource planning software, enterprise resource software, erp enterprise resource planning software, erp software, erp system">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon_io/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon_io/android-chrome-512x512.png') }}">
    <link rel="icon" href="{{ asset('favicon_io/favicon.ico') }}" type="image/x-icon">
    <link rel="manifest" href="{{ asset('favicon_io/site.webmanifest') }}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P9269JMGT5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-P9269JMGT5');
    </script>

    <script>
        document.addEventListener("livewire:navigated", function () {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: "livewire:navigated",
                page_path: window.location.pathname,
                page_title: document.title
            });
        });
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KH9FSZDD');</script>
    <!-- End Google Tag Manager -->

    @include('layouts.central.gemini.partials.styles')
    @stack('styles')

</head>
<body class="bg-slate-50 text-slate-600 antialiased overflow-x-hidden dark:bg-slate-900 dark:text-slate-300 transition-colors duration-300">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KH9FSZDD"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @include('layouts.central.gemini.partials.header')

    @yield('content')

    @include('layouts.central.gemini.partials.footer')

    @include('layouts.central.gemini.partials.scripts')
    @stack('scripts')
</body>
</html>
