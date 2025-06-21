<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('adminBoard') }}/plugins/images/favicon.png">
    <title>POS | @yield('title')</title>
    @include('admin.layout.styles')
    @stack('styles')
    @livewireStyles
</head>

<body class="mini-sidebar">
    <div id="wrapper">
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>
        @include('admin.layout.topNav')

        @include('admin.layout.sideNav')
        <div class="page-wrapper">
            @yield('content')
            <footer class="footer t-a-c">
                © CODEFANZ
            </footer>
        </div>
    </div>
    @include('admin.layout.scripts')
    @stack('scripts')

    @livewire('operations')
    @livewireScripts

    <script>
    function copyThis(url){
        if (window.isSecureContext && navigator.clipboard) {
            navigator.clipboard.writeText(url);
        }else {
            unsecuredCopyToClipboard(url);
        }
    }

    const unsecuredCopyToClipboard = (text) => { const textArea = document.createElement("textarea"); textArea.value=text; document.body.appendChild(textArea); /*textArea.focus();*/textArea.select(); try{document.execCommand('copy')}catch(err){console.error('Unable to copy to clipboard',err)}document.body.removeChild(textArea)};
    </script>

</body>

</html>
