
<!-- Preconnect for Google Fonts and FontAwesome -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

<!-- Preload and async load Google Fonts -->
@if(app()->getLocale() == 'ar')
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"></noscript>
@else
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"></noscript>
@endif

<!-- Preload and async load FontAwesome -->
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

@vite('resources/css/gemini.css')

<style>
    body { font-family: '{{ app()->getLocale() == "ar" ? "Cairo" : "Plus Jakarta Sans" }}', sans-serif; }
    .glass-nav { backdrop-filter: blur(32px)!important; }
    .text-gradient {
        background: linear-gradient(to right, #00d2b4, #0d9488);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
