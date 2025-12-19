@extends('layouts.central.site.layout')

@section('title','Powerful ERP System for Business Management | Mohaaseb')

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
                            Powerful ERP System for Smarter Business Management
                        </h1>
                        <div class="fs-18px text-body text-opacity-75 mb-4">
                            Manage your entire business from one intelligent dashboard. <span
                                class="d-xl-inline d-none"><br></span>
                            Streamline sales, inventory, accounting, HR, and operations using a modern ERP <span
                                class="d-xl-inline d-none"><br></span>
                            built for speed, automation, and real-time insights.
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
                            <a href="https://test.mohaaseb.com" class="btn btn-lg btn-outline-white px-3">Explore ERP Features <i
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
                                        <div class="fw-500 text-body text-opacity-75">Active Businesses</div>
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
                                        <div class="fw-500 text-body text-opacity-75">System Uptime</div>
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
            <h2 class="mb-3 h1">About Our ERP System</h2>
            <p class="fs-16px text-body text-opacity-50 mb-5">
                A powerful, all-in-one Enterprise Resource Planning system designed to streamline operations,
                improve efficiency, and give businesses full control over sales, inventory, accounting, HR,
                and day-to-day activities.
            </p>

            <div class="row text-start g-3 gx-lg-5 gy-lg-4">

                <!-- Feature 1 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:monitor-smartphone-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>Complete Business Control</h4>
                        <p class="mb-0">Manage sales, purchases, accounting, inventory, HR, and more—all from a unified
                            dashboard.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>Customizable Modules</h4>
                        <p class="mb-0">Tailor features, workflows, permissions, and reports to suit your exact business
                            operations.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:bolt-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>Real-Time Performance</h4>
                        <p class="mb-0">Monitor sales, stock, profits, and financial reports instantly with real-time
                            analytics.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:lock-keyhole-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>Advanced Security</h4>
                        <p class="mb-0">Role-based permissions, encrypted data, and secure cloud infrastructure protect
                            your business 24/7.</p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:dialog-2-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>Multi-Branch Support</h4>
                        <p class="mb-0">Manage multiple branches, warehouses, users, and departments with centralized
                            control.</p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:help-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>24/7 Technical Support</h4>
                        <p class="mb-0">Get continuous technical assistance and guidance from our expert support team.</p>
                    </div>
                </div>

                <!-- Feature 7 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:tuning-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>Scalable Infrastructure</h4>
                        <p class="mb-0">Grow your business with a flexible system that scales smoothly as your needs
                            expand.</p>
                    </div>
                </div>

                <!-- Feature 8 -->
                <div class="col-xl-3 col-lg-4 col-sm-6 d-flex">
                    <div
                        class="w-50px h-50px bg-theme bg-opacity-15 text-theme fs-32px d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:widget-5-line-duotone"></iconify-icon>
                    </div>
                    <div class="flex-1 ps-3">
                        <h4>User-Friendly Interface</h4>
                        <p class="mb-0">Designed for simplicity, speed, and productivity—no technical experience required.
                        </p>
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
                <h2 class="mb-3 h1">Our Unique ERP Features</h2>
                <p class="fs-16px text-body text-opacity-50 mb-5">
                    Explore HUD Admin Template's standout features. <br>
                    With advanced customization and seamless integration, create powerful and stunning <br>
                    admin interfaces, enhancing productivity and user satisfaction.
                </p>
            </div>
            @php
                $sliders = App\Models\Slider::where('active', true)->orderBy('number', 'asc')->get();
            @endphp
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
                <h2 class="mb-3 text-center h1">What Our Clients Say</h2>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    Real testimonials from clients using our ERP System.
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
    {{-- <div id="blog" class="py-5 bg-component">
			<div class="container-xxl p-3 p-lg-5">
				<div class="text-center mb-5">
					<h1 class="mb-3 text-center">Our Latest Insights</h1>
					<p class="fs-16px text-body text-opacity-50 text-center mb-0">
						Dive into our blog for the latest trends, tips, and updates <br>
						on web development, design, and industry best practices. Stay informed and inspired <br>
						with expert insights and valuable resources.
					</p>
				</div>
				<div class="row g-3 g-xl-4 mb-5">
					<div class="col-xl-3 col-lg-4 col-sm-6">
						<div class="card d-flex flex-column h-100 mb-5 mb-lg-0">
							<div class="card-body">
								<img src="assets/img/landing/blog-1.jpg" alt="" class="object-fit-cover h-200px w-100 d-block">
							</div>
							<div class="flex-1 px-3 pb-0">
								<div class="mb-2">
									<span class="bg-theme bg-opacity-15 text-theme px-2 py-1 rounded small fw-bold">Web Design</span>
								</div>
								<h5>Mastering Responsive Design: A Guide for Beginners</h5>
								<p>Explore the fundamentals of responsive web design and learn essential tips to create websites that look great on any device.</p>
							</div>
							<div class="p-3 pt-0 text-body text-opacity-50">July 15, 2025</div>
							<div class="card-arrow">
								<div class="card-arrow-top-left"></div>
								<div class="card-arrow-top-right"></div>
								<div class="card-arrow-bottom-left"></div>
								<div class="card-arrow-bottom-right"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-sm-6">
						<div class="card d-flex flex-column h-100 mb-5 mb-lg-0">
							<div class="card-body">
								<img src="assets/img/landing/blog-2.jpg" alt="" class="object-fit-cover h-200px w-100 d-block">
							</div>
							<div class="flex-1 p-3 pb-0">
								<div class="mb-2">
									<span class="bg-theme bg-opacity-15 text-theme px-2 py-1 rounded small fw-bold">UXUI Design</span>
								</div>
								<h5>The Future of UI/UX Trends in 2025</h5>
								<p>Discover the latest trends shaping user interface and experience design in the digital landscape this year.</p>
							</div>
							<div class="p-3 pt-0 text-body text-opacity-50">July 11, 2025</div>
							<div class="card-arrow">
								<div class="card-arrow-top-left"></div>
								<div class="card-arrow-top-right"></div>
								<div class="card-arrow-bottom-left"></div>
								<div class="card-arrow-bottom-right"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-sm-6">
						<div class="card d-flex flex-column h-100 mb-5 mb-lg-0">
							<div class="card-body">
								<img src="assets/img/landing/blog-3.jpg" alt="" class="object-fit-cover h-200px w-100 d-block">
							</div>
							<div class="flex-1 p-3 pb-0">
								<div class="mb-2">
									<span class="bg-theme bg-opacity-15 text-theme px-2 py-1 rounded small fw-bold">Search Engine</span>
								</div>
								<h5>Effective SEO Strategies for 2025</h5>
								<p>Dive into actionable SEO strategies and tips to boost your website’s visibility and drive organic traffic.</p>
							</div>
							<div class="p-3 pt-0 text-body text-opacity-50">June 29, 2025</div>
							<div class="card-arrow">
								<div class="card-arrow-top-left"></div>
								<div class="card-arrow-top-right"></div>
								<div class="card-arrow-bottom-left"></div>
								<div class="card-arrow-bottom-right"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-sm-6">
						<div class="card d-flex flex-column h-100 mb-5 mb-lg-0">
							<div class="card-body">
								<img src="assets/img/landing/blog-4.jpg" alt="" class="object-fit-cover h-200px w-100 d-block">
							</div>
							<div class="flex-1 p-3 pb-0">
								<div class="mb-2">
									<span class="bg-theme bg-opacity-15 text-theme px-2 py-1 rounded small fw-bold">Cyber Security</span>
								</div>
								<h5>Security Essentials: Protecting Your Website from Cyber Threats</h5>
								<p>Essential security measures and best practices to safeguard your website and user data from cyber threats.</p>
							</div>
							<div class="p-3 pt-0 text-body text-opacity-50">June 27, 2025</div>
							<div class="card-arrow">
								<div class="card-arrow-top-left"></div>
								<div class="card-arrow-top-right"></div>
								<div class="card-arrow-bottom-left"></div>
								<div class="card-arrow-bottom-right"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="text-center">
					<a href="#" class="text-decoration-none text-body text-opacity-50 h6">See More Company Stories <i class="fa fa-arrow-right ms-3"></i></a>
				</div>
			</div>
		</div> --}}
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
                <h2 class="mb-3 text-center h1">Get in Touch</h2>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    Contact us today to explore how our team can assist you. <br>
                    Whether you have inquiries, need support, or want to discuss a partnership, <br>
                    we're here to help. Reach out to us and let's start a conversation!
                </p>
            </div>
            <div class="row gx-3 gx-lg-5">
                <div class="col-lg-6">
                    <h4>Contact Us to Discuss Your Project</h4>
                    <p>
                        Do you have a project in mind? We’re eager to discuss it with you. Whether you’re looking for
                        advice, have questions, or want to share your ideas, feel free to reach out.
                    </p>
                    <p>

                        Saturday - Thursday: 9:00 AM - 6:00 PM<br>
                        Friday : Closed<br> <br>

                        Phone: <a href="tel:+201558099183" class="text-theme">+20 155 8099 183</a><br>
                        Email:
                        <a href="mailto:support@mohaaseb.com" class="text-theme">support@mohaaseb.com</a>
                    </p>
                </div>
                <div class="col-lg-6">
                    <form action="{{ route('contact-us') }}" method="POST">
                        @csrf
                        <div class="row gy-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">First Name <span class="text-theme">*</span></label>
                                <input type="text" class="form-control form-control-lg fs-15px" name="fname"
                                    required>
                                @if ($errors->has('fname'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('fname') }}</div>
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label">Last Name <span class="text-theme">*</span></label>
                                <input type="text" class="form-control form-control-lg fs-15px" name="lname"
                                    required>
                                @if ($errors->has('lname'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('lname') }}</div>
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label">Email <span class="text-theme">*</span></label>
                                <input type="text" class="form-control form-control-lg fs-15px" name="email"
                                    required>
                                @if ($errors->has('email'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label">Phone <span class="text-theme">*</span></label>
                                <input type="text" class="form-control form-control-lg fs-15px" name="phone"
                                    required>
                                @if ($errors->has('phone'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message <span class="text-theme">*</span></label>
                                <textarea class="form-control form-control-lg fs-15px" name="message" rows="8" required></textarea>
                                @if ($errors->has('message'))
                                    <div class="text-danger fs-13px mt-1">{{ $errors->first('message') }}</div>
                                @endif
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-outline-theme btn-lg btn-block px-4 fs-15px">Send
                                    Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END #contact -->
@endsection
