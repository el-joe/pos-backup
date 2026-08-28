<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @if(isset($seoHtml))
    {!! $seoHtml !!}
    @elseif(isset($seoData))
    {!! seo($seoData) !!}
    @endif

    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"SoftwareApplication","name":"Mohaaseb","applicationCategory":"BusinessApplication","operatingSystem":"Web","offers":{"@type":"Offer","price":"99","priceCurrency":"SAR"}}
    </script>

    <meta name="keywords" content="mohaaseb.com, ERP software,mohaseb erp system, POS system, business management, inventory management, accounting software, sales management, purchase management, reporting software,erp,enterprise resource management software, enterprise resource planning software, enterprise resource planning software, enterprise resource software, erp enterprise resource planning software, erp software, erp system">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon_io/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon_io/android-chrome-512x512.png') }}">
    <link rel="icon" href="{{ asset('favicon_io/favicon.ico') }}" type="image/x-icon">
    <link rel="manifest" href="{{ asset('favicon_io/site.webmanifest') }}">

    @if(config('app.env') === 'production')
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P9269JMGT5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-P9269JMGT5');
    </script>

    <script>
        document.addEventListener("livewire:navigated", function() {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: "livewire:navigated",
                page_path: window.location.pathname,
                page_title: document.title
            });
        });
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-KH9FSZDD');
    </script>
    <!-- End Google Tag Manager -->
    @endif
    @include('layouts.central.gemini.partials.styles')
    @stack('styles')

</head>

<body class="bg-slate-50 text-slate-600 antialiased overflow-x-hidden dark:!bg-slate-900 dark:text-slate-300 transition-colors duration-300">
    @if(config('app.env') === 'production')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KH9FSZDD"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    @include('layouts.central.gemini.partials.header')

    @if(isset($slot))
    {{ $slot }}
    @else
    @yield('content')
    @endif
    @include('layouts.central.gemini.partials.footer')

    @include('layouts.central.gemini.partials.scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form.newsletter-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    var emailInput = form.querySelector('input[name="email"]');
                    var messageEl = form.querySelector('.newsletter-message')
                        || (form.parentElement ? form.parentElement.querySelector('.newsletter-message') : null);
                    var submitBtn = form.querySelector('button[type="submit"]');
                    var token = document.querySelector('meta[name="csrf-token"]');

                    if (!emailInput || !token) return;

                    if (submitBtn) submitBtn.disabled = true;

                    fetch('{{ route('newsletter.subscribe') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token.getAttribute('content'),
                        },
                        body: JSON.stringify({ email: emailInput.value }),
                    })
                        .then(function (response) {
                            return response.json().then(function (data) {
                                return { ok: response.ok, data: data };
                            });
                        })
                        .then(function (result) {
                            if (!messageEl) return;
                            messageEl.classList.remove('hidden');
                            if (result.ok && result.data.success) {
                                messageEl.textContent = result.data.message || 'Subscribed successfully';
                                messageEl.classList.add('text-green-400');
                                messageEl.classList.remove('text-red-400');
                                form.reset();
                            } else {
                                var errorMessage = result.data.message
                                    || (result.data.errors && Object.values(result.data.errors)[0][0])
                                    || 'Something went wrong. Please try again.';
                                messageEl.textContent = errorMessage;
                                messageEl.classList.add('text-red-400');
                                messageEl.classList.remove('text-green-400');
                            }
                        })
                        .catch(function () {
                            if (!messageEl) return;
                            messageEl.classList.remove('hidden');
                            messageEl.textContent = 'Something went wrong. Please try again.';
                            messageEl.classList.add('text-red-400');
                        })
                        .finally(function () {
                            if (submitBtn) submitBtn.disabled = false;
                        });
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>