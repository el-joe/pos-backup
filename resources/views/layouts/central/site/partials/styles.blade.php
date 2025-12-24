<!-- Preconnect: Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Preload: Hero Images (High Priority) -->
<link rel="preload" as="image" href="{{ asset('hud/assets/img/landing/cover.webp') }}" fetchpriority="high">
<link rel="preload" as="image" href="{{ asset('hud/assets/img/landing/mockup-1.webp') }}" fetchpriority="high">

<!-- Preload + Load: Vendor CSS -->
<link rel="preload"
      href="{{ asset('hud/assets/css/vendor.min.css') }}"
      as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{ asset('hud/assets/css/vendor.min.css') }}">
</noscript>

<!-- Preload + Load: App CSS -->
<link rel="preload"
      href="{{ asset('hud/assets/css/app.min.css') }}"
      as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{ asset('hud/assets/css/app.min.css') }}">
</noscript>

<!-- Lazy CSS: Lity -->
<link rel="stylesheet"
      href="{{ asset('hud/assets/plugins/lity/dist/lity.min.css') }}"
      media="print"
      onload="this.media='all'">
<noscript>
    <link rel="stylesheet" href="{{ asset('hud/assets/plugins/lity/dist/lity.min.css') }}">
</noscript>

<!-- Preload Google Font CSS -->
<link rel="preload"
      as="style"
      href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&display=swap"
      crossorigin>

<!-- Load Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
      media="print"
      onload="this.media='all'">
<noscript>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">
</noscript>

<!-- Preload Fonts -->
<link rel="preload"
      href="{{ asset('hud/assets/webfonts/fa-solid-900.woff2') }}"
      as="font"
      type="font/woff2"
      crossorigin>

<link rel="preload"
      href="{{ asset('hud/assets/css/fonts/bootstrap-icons.woff2') }}"
      as="font"
      type="font/woff2"
      crossorigin>

<!-- Header & Logo Styling -->
<style>
    .navbar-logo {
        max-height: 42px;
        width: auto;
        aspect-ratio: 3 / 1;
        object-fit: contain;
        display: block;
    }

    .app-header {
        min-height: 64px;
        display: flex;
        align-items: center;
        contain: layout paint;
    }

    @media (max-width: 768px) {
        .navbar-logo {
            max-height: 36px;
        }
    }
</style>
