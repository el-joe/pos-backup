
@if(app()->getLocale() == 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@else
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endif

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
