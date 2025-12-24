<header id="header" class="app-header navbar navbar-expand-lg p-0" role="banner">
    <nav class="container-xxl px-3 px-lg-5" aria-label="Main Navigation">

        <button class="navbar-toggler border-0 p-0 me-3 fs-24px shadow-none" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="h-2px w-25px bg-gray-500 d-block mb-1"></span>
            <span class="h-2px w-25px bg-gray-500 d-block"></span>
        </button>

        <a class="navbar-brand d-flex align-items-center me-auto px-0" href="/">
            <img src="{{ asset('mohaaseb_en_dark_2.webp') }}"
                loading="lazy"
                alt="Mohaaseb Cloud ERP Logo"
                class="navbar-logo"
                fetchpriority="high"
                >
        </a>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-uppercase small fw-semibold">
                <li class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#home' : '/#home' }}" class="nav-link">{{ __('website.nav.home') }}</a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#about' : '/#about' }}" class="nav-link">{{ __('website.nav.about') }}</a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#features' : '/#features' }}" class="nav-link">{{ __('website.nav.features') }}</a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#testimonials' : '/#testimonials' }}" class="nav-link">{{ __('website.nav.testimonials') }}</a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ request()->is('/') ? '#contact' : '/#contact' }}" class="nav-link">{{ __('website.nav.contact') }}</a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ route('pricing') }}" class="nav-link">{{ __('website.nav.pricing') }}</a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ route('blogs.index', ['lang' => app()->getLocale()]) }}" class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}">{{ __('website.nav.blogs') }}</a>
                </li>
            </ul>
        </div>

        <div class="ms-3 d-none d-lg-flex align-items-center gap-2">
            <select class="form-select form-select-sm"
                    aria-label="Select Language"
                    onchange="if (this.value) window.location.href = this.value;">
                <option value="{{ route('site.lang', 'en') }}" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>{{ __('website.nav.english') }}</option>
                <option value="{{ route('site.lang', 'ar') }}" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>{{ __('website.nav.arabic') }}</option>
            </select>
        </div>

    </nav>
</header>
