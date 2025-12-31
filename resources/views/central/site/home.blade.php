@extends('layouts.central.site.layout')

@section('title',__('website.home.title'))

@section('content')
    <!-- BEGIN #home -->
    <div id="home" class="py-5 position-relative bg-body bg-opacity-50" data-bs-theme="dark">
        <!-- BEGIN container -->
        <div class="container-xxl p-3 p-lg-5 mb-0">
            <!-- BEGIN div-hero-content -->
            <div class="div-hero-content z-3 position-relative">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-8 -->
                    <div class="col-lg-6">
                        <!-- BEGIN hero-title-desc -->
                        <h1 class="display-6 fw-600 mb-2 mt-4">
                            {{ __('website.home.section1_title') }}
                        </h1>
                        <div class="fs-18px text-body text-opacity-75 mb-4">
                            {{ __('website.home.section1_description1') }} <span
                                class="d-xl-inline d-none"><br></span>
                            {{ __('website.home.section1_description2') }} <span
                                class="d-xl-inline d-none"><br></span>
                            {{ __('website.home.section1_description3') }}
                        </div>
                        <!-- END hero-title-desc -->

                        {{-- <div class="text-body text-opacity-35 text-center2 mb-4">
								<i class="fab fa-bootstrap fa-2x fa-fw"></i>
								<i class="fab fa-node-js fa-2x fa-fw"></i>
								<i class="fab fa-vuejs fa-2x fa-fw"></i>
								<i class="fab fa-angular fa-2x fa-fw"></i>
								<i class="fab fa-react fa-2x fa-fw"></i>
								<i class="fab fa-laravel fa-2x fa-fw"></i>
								<i class="fab fa-npm fa-2x fa-fw"></i>
							</div> --}}

                        <div class="mb-2">
                            <a href="https://test.mohaaseb.com" class="btn btn-lg btn-outline-white px-3" aria-label="{{ __('website.home.explore_section') }}">{{ __('website.home.explore_section') }}
                                <i class="fa fa-arrow-right ms-2 opacity-5"></i>
                            </a>
                        </div>

                        <hr class="my-4" />

                        <!-- BEGIN row -->
                        <div class="row text-body mt-4 mb-4">
                            <!-- BEGIN col-4 -->
                            <div class="col-6 mb-3 mb-lg-0">
                                <div class="d-flex align-items-center">
                                    <div class="h1 text-body text-opacity-25 me-3">
                                        <iconify-icon icon="mdi:chart-line"></iconify-icon>
                                    </div>
                                    <div>
                                        <div class="fw-500 mb-0 h3">12k+</div>
                                        <div class="fw-500 text-body text-opacity-75">{{ __('website.home.active_business') }}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- END col-4 -->
                            <!-- BEGIN col-4 -->
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="h1 text-body text-opacity-25 me-3">
                                        <iconify-icon icon="mdi:shield-check"></iconify-icon>
                                    </div>
                                    <div>
                                        <div class="fw-500 mb-0 h3">99.9%</div>
                                        <div class="fw-500 text-body text-opacity-75">{{ __('website.home.system_uptime') }}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- END col-4 -->
                        </div>
                        <!-- END row -->
                    </div>
                    <!-- END col-8 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END div-hero-content -->

            <div
                class="position-absolute top-0 bottom-0 end-0 w-50 p-5 z-2 overflow-hidden d-lg-flex align-items-center d-none">
                    <img
                    src="{{ asset('hud/assets/img/landing/mockup-1.webp') }}"
                    alt="ERP dashboard showing sales, inventory, and accounting modules"
                    loading="lazy"
                    width="800"
                    height="600"
                    fetchpriority="high"
                    decoding="async"
                    class="w-100 d-block shadow-lg">
            </div>
        </div>
        <!-- END container -->
        <div class="position-absolute bg-size-cover bg-position-center bg-no-repeat top-0 start-0 w-100 h-100"
            style="background-image: url({{ asset('hud/assets/img/landing/cover.webp') }});"
            role="presentation"
            aria-hidden="true">
        </div>

        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-95"
            style="background: var(--bs-body-bg-gradient);"
            role="presentation"
            aria-hidden="true">
        </div>

        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-95"
            style="background-image: url({{ asset('hud/assets/css/images/pattern-dark.png') }}); background-size: var(--bs-body-bg-image-size);"
            role="presentation"
            aria-hidden="true">
        </div>
    </div>
    <!-- END #home -->

    <section id="about" class="py-5 bg-component" aria-labelledby="about-heading">
        <div class="container-xxl p-3 p-lg-5 text-center">
            <h2 class="mb-3 h1">{{ __('website.home.about_title') }}</h2>
            <p class="fs-16px text-body text-opacity-50 mb-5">
                {{ __('website.home.about_description') }}
            </p>

            <div class="row text-start g-3 gx-lg-5 gy-lg-4" itemscope itemtype="https://schema.org/ItemList">

                <!-- Feature 1 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:monitor-smartphone-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h3 class="h4" itemprop="name">{{ __('website.home.feature1_title') }}</h3>
                        <p class="mb-0" itemprop="description">{{ __('website.home.feature1_description') }}</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h3 class="h4" itemprop="name">{{ __('website.home.feature2_title') }}</h3>
                        <p class="mb-0" itemprop="description">{{ __('website.home.feature2_description') }}</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:bolt-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h3 class="h4" itemprop="name">{{ __('website.home.feature3_title') }}</h3>
                        <p class="mb-0" itemprop="description">{{ __('website.home.feature3_description') }}</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:lock-keyhole-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h3 class="h4" itemprop="name">{{ __('website.home.feature4_title') }}</h3>
                        <p class="mb-0" itemprop="description">{{ __('website.home.feature4_description') }}</p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:dialog-2-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h3 class="h4" itemprop="name">{{ __('website.home.feature5_title') }}</h3>
                        <p class="mb-0" itemprop="description">{{ __('website.home.feature5_description') }}</p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:help-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h3 class="h4" itemprop="name">{{ __('website.home.feature6_title') }}</h3>
                        <p class="mb-0" itemprop="description">{{ __('website.home.feature6_description') }}</p>
                    </div>
                </div>

                <!-- Feature 7 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:tuning-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h3 class="h4" itemprop="name">{{ __('website.home.feature7_title') }}</h3>
                        <p class="mb-0" itemprop="description">{{ __('website.home.feature7_description') }}</p>
                    </div>
                </div>

                <!-- Feature 8 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:widget-5-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h3 class="h4" itemprop="name">{{ __('website.home.feature8_title') }}</h3>
                        <p class="mb-0" itemprop="description">{{ __('website.home.feature8_description') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-1 m-0" aria-hidden="true" />
    </div>
    <!-- END divider -->

    <!-- BEGIN #features -->
    <section id="features" class="py-5 position-relative" aria-labelledby="features-heading">
        <div class="container-xxl p-3 p-lg-5 z-2 position-relative">
            <div class="text-center mb-5">
                <h2 class="mb-3 h1">{{ __('website.home.our_unique_erp_features') }}</h2>
                <p class="fs-16px text-body text-opacity-50 mb-5">
                    {{ __('website.home.explore_erp_features_description') }}
                </p>
            </div>
            <div class="row g-3 g-lg-5" itemscope itemtype="https://schema.org/ImageGallery">
                @foreach ($sliders as $item)
                    <div class="col-xl-3 col-lg-4 col-sm-6" itemprop="associatedMedia" itemscope itemtype="https://schema.org/ImageObject">
                        <a href="{{ $item->image_path }}" data-lity class="shadow d-block" itemprop="contentUrl" aria-label="{{ $item->title }} ERP Feature">
                            <img
                                src="{{ $item->image_path }}"
                                alt="{{ $item->title }} ERP feature"
                                itemprop="thumbnail"
                                loading="lazy"
                                width="400"
                                height="170"
                                class="w-100 h-170px">
                        </a>
                        <div class="text-center my-3 text-body fw-bold" itemprop="caption">{{ $item->title }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- END #features -->

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-4 m-0" aria-hidden="true" />
    </div>
    <!-- END divider -->

    <!-- BEGIN #testimonials -->
    <section id="testimonials" class="py-5 text-body text-opacity-75" aria-labelledby="testimonials-heading">
        <div class="container-xxl p-3 p-lg-5">
            <div class="text-center mb-5">
                <h2 class="mb-3 text-center h1">{{ __('website.home.our_clients_says') }}</h2>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    {{ __('website.home.our_clients_says_subtitle') }}
                </p>
            </div>

            @php
                $images = [
                    'male' => "data:image/svg+xml;utf8,
                            <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'>
                            <circle cx='12' cy='7' r='4'/>
                            <path d='M5.5 21a6.5 6.5 0 0 1 13 0'/>
                            </svg>",
                    'famale' => "data:image/svg+xml;utf8,
                            <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'>
                            <circle cx='12' cy='7' r='4'/>
                            <path d='M4 21c0-4 3-7 8-7s8 3 8 7'/>
                            <path d='M8 11c0-3 2-6 4-6s4 3 4 6'/>
                            </svg>",
                ];
            @endphp
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-xl-4 col-md-6" itemscope itemtype="https://schema.org/Review">
                    <span class="d-none" itemprop="itemReviewed" itemscope itemtype="https://schema.org/SoftwareApplication">
                        <meta itemprop="name" content="Mohaaseb ERP">
                        <meta itemprop="applicationCategory" content="BusinessApplication">
                    </span>
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['male'] }}" class="rounded-circle me-3 w-50px" alt="User Avatar" role="presentation">
                            <div itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <h3 class="h5 mb-0" itemprop="name">Daniel Carter</h3>
                                <small class="text-muted" itemprop="jobTitle">Senior Operations Director</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2" aria-label="5 star rating">
                                    <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="5">
                                        <meta itemprop="bestRating" content="5">
                                    </span>
                                    @for($i=0; $i<5; $i++)
                                        <iconify-icon icon="ic:baseline-star" class="fs-18px" aria-hidden="true"></iconify-icon>
                                    @endfor
                                </div>
                                <div itemprop="reviewBody">
                                    The ERP system streamlined our workflow.
                                    Inventory, accounting, and POS finally work in harmony.
                                </div>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            </div>
                        </div>

                        <div class="card-arrow" aria-hidden="true">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6" itemscope itemtype="https://schema.org/Review">
                    <span class="d-none" itemprop="itemReviewed" itemscope itemtype="https://schema.org/SoftwareApplication">
                        <meta itemprop="name" content="Mohaaseb ERP">
                        <meta itemprop="applicationCategory" content="BusinessApplication">
                    </span>
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['male'] }}" class="rounded-circle me-3 w-50px" alt="" role="presentation">
                            <div itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <h5 class="mb-0" itemprop="name">محمد ياسر</h5>
                                <small class="text-muted" itemprop="jobTitle">مدير التطوير الرقمي</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2" aria-label="5 star rating">
                                    <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="5">
                                        <meta itemprop="bestRating" content="5">
                                    </span>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                <div itemprop="reviewBody" dir="rtl">
                                    نظام الـ ERP ساعدنا في تنظيم الحسابات والمخزون بشكل احترافي
                                    وأصبح اتخاذ القرار أسرع وأكثر دقة.
                                </div>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            </div>
                        </div>

                        <div class="card-arrow" aria-hidden="true">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6" itemscope itemtype="https://schema.org/Review">
                    <span class="d-none" itemprop="itemReviewed" itemscope itemtype="https://schema.org/SoftwareApplication">
                        <meta itemprop="name" content="Mohaaseb ERP">
                        <meta itemprop="applicationCategory" content="BusinessApplication">
                    </span>
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['famale'] }}" class="rounded-circle me-3 w-50px" alt="" role="presentation">
                            <div itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <h5 class="mb-0" itemprop="name">Sophia Williams</h5>
                                <small class="text-muted" itemprop="jobTitle">Finance &amp; Compliance Lead</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2" aria-label="5 star rating">
                                    <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="5">
                                        <meta itemprop="bestRating" content="5">
                                    </span>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                <div itemprop="reviewBody">
                                    A reliable system with perfect financial tracking.
                                    VAT, reports, and analytics are incredibly accurate.
                                </div>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            </div>
                        </div>

                        <div class="card-arrow" aria-hidden="true">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-xl-2 d-none d-xl-block"></div> --}}
                <div class="col-xl-4 col-md-6" itemscope itemtype="https://schema.org/Review">
                    <span class="d-none" itemprop="itemReviewed" itemscope itemtype="https://schema.org/SoftwareApplication">
                        <meta itemprop="name" content="Mohaaseb ERP">
                        <meta itemprop="applicationCategory" content="BusinessApplication">
                    </span>
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['famale'] }}" class="rounded-circle me-3 w-50px" alt="" role="presentation">
                            <div itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <h5 class="mb-0" itemprop="name">ليلى عمر</h5>
                                <small class="text-muted" itemprop="jobTitle">قائدة قسم الجودة</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2" aria-label="5 star rating">
                                    <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="5">
                                        <meta itemprop="bestRating" content="5">
                                    </span>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                <div itemprop="reviewBody" dir="rtl">
                                    النظام سهّل علينا متابعة الفروع
                                    وربط جميع العمليات بشكل سلس وبدون تعقيد.
                                </div>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            </div>
                        </div>

                        <div class="card-arrow" aria-hidden="true">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6" itemscope itemtype="https://schema.org/Review">
                    <span class="d-none" itemprop="itemReviewed" itemscope itemtype="https://schema.org/SoftwareApplication">
                        <meta itemprop="name" content="Mohaaseb ERP">
                        <meta itemprop="applicationCategory" content="BusinessApplication">
                    </span>
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['male'] }}" class="rounded-circle me-3 w-50px" alt="" role="presentation">
                            <div itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <h5 class="mb-0" itemprop="name">خالد سمير</h5>
                                <small class="text-muted" itemprop="jobTitle">محلل نظم الأعمال</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2" aria-label="5 star rating">
                                    <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="5">
                                        <meta itemprop="bestRating" content="5">
                                    </span>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                <div itemprop="reviewBody" dir="rtl">
                                    نظام ممتاز وسلس،
                                    ويوفر تقارير قوية تساعد الإدارة في رؤية واضحة لكل تفاصيل العمل.
                                </div>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            </div>
                        </div>

                        <div class="card-arrow" aria-hidden="true">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6" itemscope itemtype="https://schema.org/Review">
                    <span class="d-none" itemprop="itemReviewed" itemscope itemtype="https://schema.org/SoftwareApplication">
                        <meta itemprop="name" content="Mohaaseb ERP">
                        <meta itemprop="applicationCategory" content="BusinessApplication">
                    </span>
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['male'] }}" class="rounded-circle me-3 w-50px" alt="" role="presentation">
                            <div itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <h5 class="mb-0" itemprop="name">Mark Henderson</h5>
                                <small class="text-muted" itemprop="jobTitle">Supply Chain Strategist</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2" aria-label="5 star rating">
                                    <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        <meta itemprop="ratingValue" content="5">
                                        <meta itemprop="bestRating" content="5">
                                    </span>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                <div itemprop="reviewBody">
                                    Stock control accuracy improved by 300%.
                                    Perfect solution for multi-branch businesses.
                                </div>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15" aria-hidden="true"></i>
                            </div>
                        </div>

                        <div class="card-arrow" aria-hidden="true">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END #testimonials -->

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-4 m-0" aria-hidden="true" />
    </div>
    <!-- END divider -->

    <!-- BEGIN #blog -->
    <section id="blog" class="py-5 bg-component" aria-labelledby="blog-heading">
        <div class="container-xxl p-3 p-lg-5">
            <div class="text-center mb-5">
                <h2 id="blog-heading" class="mb-3 h1 text-center">{{ __('website.home.our_insights_title') }}</h2>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    {!! __('website.home.our_insights_subtitle') !!}
                </p>
            </div>

            <div class="row g-3 g-xl-4 mb-5">
                @foreach ($blogs as $blog)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <article class="card d-flex flex-column h-100 mb-5 mb-lg-0" itemscope itemtype="https://schema.org/BlogPosting">
                            <div style="flex:none!important" class="card-body p-0 overflow-hidden">
                                <a href="{{ route('blogs.show', ['slug' => $blog->slug, 'lang' => app()->getLocale()]) }}" aria-label="Read more about {{ $blog->title }}">
                                    <img
                                        loading="lazy"
                                        src="{{ $blog->thumb_image_path }}"
                                        alt="{{ $blog->title }}"
                                        itemprop="image"
                                        width="400"
                                        height="200"
                                        class="object-fit-cover h-200px w-100 d-block">
                                </a>
                            </div>

                            <div class="flex-1 px-3 pt-3 pb-0">
                                <h3 class="h5 mb-2" itemprop="headline">
                                    <a href="{{ route('blogs.show', ['slug' => $blog->slug, 'lang' => app()->getLocale()]) }}" class="text-decoration-none text-body">
                                        {{ $blog->title }}
                                    </a>
                                </h3>
                                <p itemprop="description" class="mb-0 text-opacity-75">
                                    {{ \Illuminate\Support\Str::limit($blog->excerpt ?: strip_tags($blog->content), 120) }}
                                </p>
                            </div>

                            <div class="p-3 pt-2 d-flex justify-content-between align-items-center">
                                <time itemprop="datePublished"
                                    datetime="{{ optional($blog->published_at ?: $blog->created_at)->toIso8601String() }}"
                                    class="small text-body text-opacity-50">
                                    {{ optional($blog->published_at ?: $blog->created_at)->translatedFormat('M d, Y') }}
                                </time>

                                <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" class="d-none">
                                    <meta itemprop="name" content="Mohaaseb">
                                </div>
                            </div>

                            <div class="card-arrow" aria-hidden="true">
                                <div class="card-arrow-top-left"></div>
                                <div class="card-arrow-top-right"></div>
                                <div class="card-arrow-bottom-left"></div>
                                <div class="card-arrow-bottom-right"></div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('blogs.index', ['lang' => app()->getLocale()]) }}" class="btn btn-link text-decoration-none text-body text-opacity-50 h6">
                    {{ __('website.home.see_more_stories') }} <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>
    <!-- END #blog -->

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-4 m-0" aria-hidden="true" />
    </div>
    <!-- END divider -->

    <!-- BEGIN #contact -->
    <section id="contact" class="py-5 text-body text-opacity-75" aria-labelledby="contact-heading">
        <div class="container-xl p-3 p-lg-5">
            <div class="text-center mb-5">
                <h2 id="contact-heading" class="mb-3 text-center h1">{{ __('website.home.get_in_touch') }}</h2>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    {!! __('website.home.get_in_touch_p') !!}
                </p>
            </div>

            <div class="row gx-3 gx-lg-5" itemscope itemtype="https://schema.org/Organization">
                <meta itemprop="name" content="Mohaaseb ERP">

                <div class="col-lg-6">
                    <h3 class="h4">{{ __('website.home.contacts_title') }}</h3>
                    <div itemprop="description">
                        <p>{!! __('website.home.contacts_description') !!}</p>
                    </div>

                    <div class="mt-4" itemprop="contactPoint" itemscope itemtype="https://schema.org/ContactPoint">
                        <p class="mb-1">
                            <strong>{{ __('website.home.working_hours') }}:</strong><br>
                            {!! __('website.home.contacts_hours') !!}
                        </p>
                        <meta itemprop="contactType" content="customer service">
                    </div>
                </div>

                <div class="col-lg-6">
                    <form action="{{ route('contact-us') }}" method="POST">
                        @csrf
                        <div class="row gy-3 mb-3">
                            <div class="col-6">
                                <label for="fname" class="form-label">{{ __('website.home.contact_us_first_name') }} <span class="text-theme">*</span></label>
                                <input type="text" id="fname" class="form-control form-control-lg fs-15px" name="fname" required>
                                @error('fname')
                                    <div class="text-danger fs-13px mt-1" role="alert">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label for="lname" class="form-label">{{ __('website.home.contact_us_last_name') }} <span class="text-theme">*</span></label>
                                <input type="text" id="lname" class="form-control form-control-lg fs-15px" name="lname" required>
                                @error('lname')
                                    <div class="text-danger fs-13px mt-1" role="alert">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label for="email" class="form-label">{{ __('website.home.contact_us_email') }} <span class="text-theme">*</span></label>
                                <input type="email" id="email" class="form-control form-control-lg fs-15px" name="email" required>
                                @error('email')
                                    <div class="text-danger fs-13px mt-1" role="alert">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label for="phone" class="form-label">{{ __('website.home.contact_us_phone') }} <span class="text-theme">*</span></label>
                                <input type="tel" id="phone" class="form-control form-control-lg fs-15px" name="phone" required>
                                @error('phone')
                                    <div class="text-danger fs-13px mt-1" role="alert">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label">{{ __('website.home.contact_us_message') }} <span class="text-theme">*</span></label>
                                <textarea id="message" class="form-control form-control-lg fs-15px" name="message" rows="8" required></textarea>
                                @error('message')
                                    <div class="text-danger fs-13px mt-1" role="alert">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-outline-theme btn-lg btn-block px-4 fs-15px">
                                    {{ __('website.home.contact_us_send_message') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- END #contact -->
@endsection
