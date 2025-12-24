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
        /* Set max-height but allow width to be calculated */
        max-height: 50px;
        width: auto;
        /* Important: Define the native aspect ratio of your logo here */
        /* Example: if your logo is 150x50, the ratio is 3/1 */
        aspect-ratio: 3 / 1;
        display: block;
        /* Ensures the logo looks sharp and fits its container */
        object-fit: contain;
    }

    .app-header {
        min-height: 64px;
        display: flex;
        align-items: center;
        /* Optimization: contain paint to help browser rendering performance */
        contain: layout;
    }

    @media (max-width: 768px) {
        .navbar-logo {
            max-height: 40px; /* Slightly smaller for mobile UX */
        }
    }
</style>
