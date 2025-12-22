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
                            <a href="https://test.mohaaseb.com" class="btn btn-lg btn-outline-white px-3">{{ __('website.home.explore_section') }} <i
                                    class="fa fa-arrow-right ms-2 opacity-5"></i></a>
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
                    src="{{ asset('hud/assets/img/landing/mockup-1.jpg') }}"
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
        <div class="position-absolute bg-size-cover bg-position-center d-none2 bg-no-repeat top-0 start-0 w-100 h-100"
            style="background-image: url({{ asset('hud/assets/img/landing/cover.jpg') }});"></div>
        <div class="position-absolute top-0 start-0 d-none2 w-100 h-100 opacity-95"
            style="background: var(--bs-body-bg-gradient);"></div>
        <div class="position-absolute top-0 start-0 d-none2 w-100 h-100 opacity-95"
            style="background-image: url({{ asset('hud/assets/css/images/pattern-dark.png') }}); background-size: var(--bs-body-bg-image-size);">
        </div>
    </div>
    <!-- END #home -->

    <div id="about" class="py-5 bg-component">
        <div class="container-xxl p-3 p-lg-5 text-center">
            <h2 class="mb-3 h1">{{ __('website.home.about_title') }}</h2>
            <p class="fs-16px text-body text-opacity-50 mb-5">
                {{ __('website.home.about_description') }}
            </p>

            <div class="row text-start g-3 gx-lg-5 gy-lg-4">

                <!-- Feature 1 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:monitor-smartphone-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>{{ __('website.home.feature1_title') }}</h4>
                        <p class="mb-0">{{ __('website.home.feature1_description') }}</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>{{ __('website.home.feature2_title') }}</h4>
                        <p class="mb-0">{{ __('website.home.feature2_description') }}</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:bolt-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>{{ __('website.home.feature3_title') }}</h4>
                        <p class="mb-0">{{ __('website.home.feature3_description') }}</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:lock-keyhole-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>{{ __('website.home.feature4_title') }}</h4>
                        <p class="mb-0">{{ __('website.home.feature4_description') }}</p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:dialog-2-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>{{ __('website.home.feature5_title') }}</h4>
                        <p class="mb-0">{{ __('website.home.feature5_description') }}</p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:help-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>{{ __('website.home.feature6_title') }}</h4>
                        <p class="mb-0">{{ __('website.home.feature6_description') }}</p>
                    </div>
                </div>

                <!-- Feature 7 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:tuning-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>{{ __('website.home.feature7_title') }}</h4>
                        <p class="mb-0">{{ __('website.home.feature7_description') }}</p>
                    </div>
                </div>

                <!-- Feature 8 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:widget-5-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>{{ __('website.home.feature8_title') }}</h4>
                        <p class="mb-0">{{ __('website.home.feature8_description') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-4 m-0" />
    </div>
    <!-- END divider -->

    <!-- BEGIN #features -->
    <div id="features" class="py-5 position-relative">
        <div class="container-xxl p-3 p-lg-5 z-2 position-relative">
            <div class="text-center mb-5">
                <h2 class="mb-3 h1">{{ __('website.home.our_unique_erp_features') }}</h2>
                <p class="fs-16px text-body text-opacity-50 mb-5">
                    {{ __('website.home.explore_erp_features_description') }}
                </p>
            </div>
            <div class="row g-3 g-lg-5">
                @foreach ($sliders as $item)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <a href="{{ $item->image_path }}" data-lity class="shadow d-block">
                            <img
                                src="{{ $item->image_path }}"
                                alt="{{ $item->title }} ERP feature"
                                loading="lazy"
                                width="400"
                                height="170"
                                class="w-100 h-170px">
                        </a>
                        <div class="text-center my-3 text-body fw-bold">{{ $item->title }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- END #features -->

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-4 m-0" />
    </div>
    <!-- END divider -->

    @livewire('central.site.plan-section')

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-4 m-0" />
    </div>
    <!-- END divider -->

    <!-- BEGIN #testimonials -->
    <div id="testimonials" class="py-5 text-body text-opacity-75">
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
                <div class="col-xl-4 col-md-6">
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['male'] }}" class="rounded-circle me-3 w-50px">
                            <div>
                                <h5 class="mb-0">Daniel Carter</h5>
                                <small class="text-muted">Senior Operations Director</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2">
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                The ERP system streamlined our workflow.
                                Inventory, accounting, and POS finally work in harmony.
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15"></i>
                            </div>
                        </div>

                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['male'] }}" class="rounded-circle me-3 w-50px">
                            <div>
                                <h5 class="mb-0">محمد ياسر</h5>
                                <small class="text-muted">مدير التطوير الرقمي</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2">
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                <span dir="rtl">
                                    نظام الـ ERP ساعدنا في تنظيم الحسابات والمخزون بشكل احترافي
                                    وأصبح اتخاذ القرار أسرع وأكثر دقة.
                                </span>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15"></i>
                            </div>
                        </div>

                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['famale'] }}" class="rounded-circle me-3 w-50px">
                            <div>
                                <h5 class="mb-0">Sophia Williams</h5>
                                <small class="text-muted">Finance & Compliance Lead</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2">
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                A reliable system with perfect financial tracking.
                                VAT, reports, and analytics are incredibly accurate.
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15"></i>
                            </div>
                        </div>

                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-xl-2 d-none d-xl-block"></div> --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['famale'] }}" class="rounded-circle me-3 w-50px">
                            <div>
                                <h5 class="mb-0">ليلى عمر</h5>
                                <small class="text-muted">قائدة قسم الجودة</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2">
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                <span dir="rtl">
                                    النظام سهّل علينا متابعة الفروع
                                    وربط جميع العمليات بشكل سلس وبدون تعقيد.
                                </span>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15"></i>
                            </div>
                        </div>

                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['male'] }}" class="rounded-circle me-3 w-50px">
                            <div>
                                <h5 class="mb-0">خالد سمير</h5>
                                <small class="text-muted">محلل نظم الأعمال</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2">
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                <span dir="rtl">
                                    نظام ممتاز وسلس،
                                    ويوفر تقارير قوية تساعد الإدارة في رؤية واضحة لكل تفاصيل العمل.
                                </span>
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15"></i>
                            </div>
                        </div>

                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $images['male'] }}" class="rounded-circle me-3 w-50px">
                            <div>
                                <h5 class="mb-0">Mark Henderson</h5>
                                <small class="text-muted">Supply Chain Strategist</small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="fa fa-quote-left fa-2x text-body text-opacity-15"></i>
                            <div class="p-3">
                                <div class="text-warning d-flex mb-2">
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                    <iconify-icon icon="ic:baseline-star" class="fs-18px"></iconify-icon>
                                </div>
                                Stock control accuracy improved by 300%.
                                Perfect solution for multi-branch businesses.
                            </div>
                            <div class="d-flex align-items-end">
                                <i class="fa fa-quote-right fa-2x text-body text-opacity-15"></i>
                            </div>
                        </div>

                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END #testimonials -->

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-4 m-0" />
    </div>
    <!-- END divider -->

    <!-- BEGIN #blog -->
    <div id="blog" class="py-5 bg-component">
			<div class="container-xxl p-3 p-lg-5">
				<div class="text-center mb-5">
					<h1 class="mb-3 text-center">{{ __('website.home.our_insights_title') }}</h1>
					<p class="fs-16px text-body text-opacity-50 text-center mb-0">
                        {{ __('website.home.our_insights_subtitle') }}
					</p>
				</div>
				<div class="row g-3 g-xl-4 mb-5">
                    @foreach ($blogs as $blog)
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none text-body">
                                <div class="card d-flex flex-column h-100 mb-5 mb-lg-0">
                                    <div class="card-body">
                                        <img src="{{ $blog->image ? asset($blog->image) : asset('hud/assets/img/landing/blog-1.jpg') }}" alt="{{ $blog->title }}" class="object-fit-cover h-200px w-100 d-block">
                                    </div>
                                    <div class="flex-1 px-3 pb-0">
                                        <h5 class="mb-2">{{ $blog->title }}</h5>
                                        <p class="mb-0">{{ \Illuminate\Support\Str::limit($blog->excerpt ?: strip_tags($blog->content), 120) }}</p>
                                    </div>
                                    <div class="p-3 pt-0 text-body text-opacity-50">{{ optional($blog->published_at ?: $blog->created_at)->format('M d, Y') }}</div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
				</div>
				<div class="text-center">
					<a href="{{ route('blogs.index') }}" class="text-decoration-none text-body text-opacity-50 h6">{{ __('website.home.see_more_stories') }} <i class="fa fa-arrow-right ms-3"></i></a>
				</div>
			</div>
		</div>
    <!-- END #blog -->

    <!-- BEGIN divider -->
    <div class="container-xxl px-3 px-lg-5">
        <hr class="opacity-4 m-0" />
    </div>
    <!-- END divider -->

    <!-- BEGIN #contact -->
    <div id="contact" class="py-5 text-body text-opacity-75">
        <div class="container-xl p-3 p-lg-5">
            <div class="text-center mb-5">
                <h2 class="mb-3 text-center h1">{{ __('website.home.get_in_touch') }}</h2>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    {!! __('website.home.get_in_touch_p') !!}
                </p>
            </div>
            <div class="row gx-3 gx-lg-5">
                <div class="col-lg-6">
                    <h4>{{ __('website.home.contacts_title') }}</h4>
                    <p>
                        {!! __('website.home.contacts_description') !!}
                    </p>
                    <p>
                        {!! __('website.home.contacts_hours') !!}
                    </p>
                </div>
                <div class="col-lg-6">
                    <form action="{{ route('contact-us') }}" method="POST">
                        @csrf
                        <div class="row gy-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">{{ __('website.home.contact_us_first_name') }} <span class="text-theme">*</span></label>
                                <input type="text" class="form-control form-control-lg fs-15px" name="fname"
                                    required>
                                @if ($errors->has('fname'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('fname') }}</div>
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('website.home.contact_us_last_name') }} <span class="text-theme">*</span></label>
                                <input type="text" class="form-control form-control-lg fs-15px" name="lname"
                                    required>
                                @if ($errors->has('lname'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('lname') }}</div>
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('website.home.contact_us_email') }} <span class="text-theme">*</span></label>
                                <input type="text" class="form-control form-control-lg fs-15px" name="email"
                                    required>
                                @if ($errors->has('email'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('website.home.contact_us_phone') }} <span class="text-theme">*</span></label>
                                <input type="text" class="form-control form-control-lg fs-15px" name="phone"
                                    required>
                                @if ($errors->has('phone'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('website.home.contact_us_message') }} <span class="text-theme">*</span></label>
                                <textarea class="form-control form-control-lg fs-15px" name="message" rows="8" required></textarea>
                                @if ($errors->has('message'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('message') }}</div>
                                @endif
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-outline-theme btn-lg btn-block px-4 fs-15px">{{ __('website.home.contact_us_send_message') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END #contact -->
@endsection
