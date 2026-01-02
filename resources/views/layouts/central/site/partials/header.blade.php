<header id="header" class="app-header navbar navbar-expand-lg p-0" role="banner">
    <nav class="container-xxl px-3 px-lg-5" aria-label="Main Navigation">

        <button class="navbar-toggler border-0 p-0 me-3 fs-24px shadow-none" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="h-2px w-25px bg-gray-500 d-block mb-1"></span>
            <span class="h-2px w-25px bg-gray-500 d-block"></span>
        </button>

        <a class="navbar-brand d-flex align-items-center px-0 ms-auto ms-lg-0 me-lg-auto" href="/">
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
                    <a href="{{ route('blogs.index', ['lang' => $__currentLang]) }}" class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}">{{ __('website.nav.blogs') }}</a>
                </li>
                <li class="nav-item dropdown ms-lg-3">
                    <button
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 rounded-pill px-3"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        aria-label="Select Language">

                        <i class="bi bi-globe fs-14px"></i>

                        <span class="fw-semibold text-uppercase">
                            {{ app()->getLocale() }}
                        </span>

                        <i class="bi bi-chevron-down fs-12px opacity-75"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2 p-1">
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between rounded"
                            href="{{ route('site.lang', ['locale' => 'en']) }}">
                                <span>English</span>
                                @if (app()->getLocale() === 'en')
                                    <i class="bi bi-check2 text-primary"></i>
                                @endif
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between rounded"
                            href="{{ route('site.lang', ['locale' => 'ar']) }}">
                                <span>العربية</span>
                                @if (app()->getLocale() === 'ar')
                                    <i class="bi bi-check2 text-primary"></i>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
