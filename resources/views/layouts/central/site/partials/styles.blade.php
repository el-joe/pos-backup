<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link rel="preload" as="image" href="{{ asset('hud/assets/img/landing/cover.jpg') }}" fetchpriority="high">
<link rel="preload" as="image" href="{{ asset('hud/assets/img/landing/mockup-1.jpg') }}" fetchpriority="high">

<link rel="preload" href="{{ asset('hud/assets/css/vendor.min.css') }}" as="style">
<link rel="preload" href="{{ asset('hud/assets/css/app.min.css') }}" as="style">

<link rel="stylesheet" href="{{ asset('hud/assets/css/vendor.min.css') }}">
<link rel="stylesheet" href="{{ asset('hud/assets/css/app.min.css') }}">

<link rel="stylesheet" href="{{ asset('hud/assets/plugins/lity/dist/lity.min.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('hud/assets/plugins/lity/dist/lity.min.css') }}"></noscript>

<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&display=swap">

<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

<noscript>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</noscript>

<style>
    .navbar-logo {
        max-height: 50px;
        width: auto;
        display: block;
    }
    .app-header {
        min-height: 64px;
        display: flex;
        align-items: center;
    }

    @media (max-width: 768px) {
        .navbar-logo {
            max-height: 45px;
        }
    }
</style>
