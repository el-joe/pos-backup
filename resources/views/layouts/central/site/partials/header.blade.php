<!-- BEGIN #header -->
<div id="header" class="app-header navbar navbar-expand-lg p-0">
    <div class="container-xxl px-3 px-lg-5">

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0 p-0 me-3 fs-24px shadow-none" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="h-2px w-25px bg-gray-500 d-block mb-1"></span>
            <span class="h-2px w-25px bg-gray-500 d-block"></span>
        </button>

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center me-auto px-0" href="/">
            <img src="{{ asset('mohaaseb_en_dark.png') }}"
                 alt="Mohaaseb Logo"
                 class="d-inline-block"
                 height="50">
        </a>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="navbar-nav ms-auto mb-2 mb-lg-0 text-uppercase small fw-semibold">

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#home' : '/#home' }}" class="nav-link">Home</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#about' : '/#about' }}" class="nav-link">About</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#features' : '/#features' }}" class="nav-link">Features</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#pricing' : '/#pricing' }}" class="nav-link">Pricing</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#testimonials' : '/#testimonials' }}" class="nav-link">Testimonials</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#contact' : '/#contact' }}" class="nav-link">Contact</a>
                </div>
            </div>
        </div>

        <!-- Button -->
        <div class="ms-3 d-none d-lg-block">
            <a href="index.html" class="btn btn-outline-theme btn-sm fw-semibold text-uppercase px-2 py-1 fs-10px">
                Get started <i class="fa fa-arrow-right ms-1"></i>
            </a>
        </div>

    </div>
</div>
<!-- END #header -->
