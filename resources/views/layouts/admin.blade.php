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
    <title>POS System</title>
    @include('layouts.admin.partials.styles')
    @stack('styles')
    @livewireStyles
</head>

<body class="mini-sidebar">
    <div id="wrapper">
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>
        @include('layouts.admin.partials.topnav')
        @include('layouts.admin.partials.sidebar')
        <div class="page-wrapper">
            <div class="container-fluid" style="min-height: 822px;">
                {{ $slot }}
            </div>
        </div>
    </div>
    <footer class="footer t-a-c">
        © POS
    </footer>

    @include('layouts.admin.partials.scripts')
    @livewireScripts
    @stack('scripts')
    @livewire('operations')
</body>

</html>
