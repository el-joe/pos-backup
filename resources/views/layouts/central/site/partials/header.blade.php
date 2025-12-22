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
                 alt="{{ __('website.header.logo_alt') }}"
                 class="d-inline-block"
                 height="50">
        </a>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="navbar-nav ms-auto mb-2 mb-lg-0 text-uppercase small fw-semibold">

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#home' : '/#home' }}" class="nav-link">{{ __('website.nav.home') }}</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#about' : '/#about' }}" class="nav-link">{{ __('website.nav.about') }}</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#features' : '/#features' }}" class="nav-link">{{ __('website.nav.features') }}</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#pricing' : '/#pricing' }}" class="nav-link">{{ __('website.nav.pricing') }}</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#testimonials' : '/#testimonials' }}" class="nav-link">{{ __('website.nav.testimonials') }}</a>
                </div>


                <div class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#contact' : '/#contact' }}" class="nav-link">{{ __('website.nav.contact') }}</a>
                </div>

                <div class="nav-item me-2">
                    <a href="{{ route('blogs.index') }}" class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}">{{ __('website.nav.blogs') }}</a>
                </div>
            </div>
        </div>

        <div class="ms-3 d-none d-lg-flex align-items-center gap-2">
            <select class="form-select form-select-sm" onchange="if (this.value) window.location.href = this.value;">
                <option value="{{ route('site.lang', 'en') }}" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>{{ __('website.nav.english') }}</option>
                <option value="{{ route('site.lang', 'ar') }}" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>{{ __('website.nav.arabic') }}</option>
            </select>

        </div>

    </div>
</div>
<!-- END #header -->
