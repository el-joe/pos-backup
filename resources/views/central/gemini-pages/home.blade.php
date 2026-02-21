@extends('layouts.central.gemini.layout')

@section('title', __('gemini-landing.home.page_title'))

@section('content')
<main itemscope itemtype="https://schema.org/WebPage">
<header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-gradient-to-b from-brand-50 to-white dark:from-slate-900 dark:to-slate-800 transition-colors duration-300">
        <div class="absolute top-0 right-0 -z-10 w-1/2 h-full bg-brand-100 dark:bg-brand-900/20 opacity-50 blur-[100px] rounded-bl-full"></div>

        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-16">

                <div class="lg:w-1/2" data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white dark:bg-slate-800 border border-brand-100 dark:border-slate-700 shadow-sm text-brand-600 dark:text-brand-400 text-sm font-bold mb-6">
                        <i class="fa-solid fa-check-circle"></i> {{ __('gemini-landing.home.badge_vat_secure') }}
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-brand-dark dark:text-white leading-tight mb-6">
                        {{ __('gemini-landing.home.hero_title_prefix') }} <br>
                        <span class="text-gradient">{{ __('gemini-landing.home.hero_title_product') }}</span>
                    </h1>
                    <p class="text-lg text-slate-500 dark:text-slate-400 mb-8 max-w-lg leading-relaxed">
                        {{ __('gemini-landing.home.hero_subtitle_prefix') }}
                        <strong>{{ __('gemini-landing.home.accounting') }}</strong>,
                        <strong>{{ __('gemini-landing.home.hr') }}</strong>,
                        <strong>{{ __('gemini-landing.home.pos') }}</strong>,
                        {{ __('gemini-landing.home.hero_subtitle_and') }}
                        <strong>{{ __('gemini-landing.home.booking') }}</strong>
                        {{ __('gemini-landing.home.hero_subtitle_suffix') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('contact') }}" class="px-8 py-4 bg-brand-500 text-white rounded-xl font-bold hover:bg-brand-600 transition shadow-xl shadow-brand-500/20 flex items-center justify-center gap-2">
                            {{ __('gemini-landing.home.cta_request_demo') }} <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="#features" class="px-8 py-4 bg-white dark:bg-slate-800 text-brand-dark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl font-bold hover:border-brand-500 hover:text-brand-500 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-play"></i> {{ __('gemini-landing.home.cta_watch_video') }}
                        </a>
                    </div>
                </div>

                <div class="lg:w-1/2 relative" data-aos="fade-left" data-aos-duration="1200">
                    <div class="relative z-10 animate-float">
                        <div class="rounded-2xl shadow-2xl overflow-hidden border-4 border-white dark:border-slate-700 bg-slate-900">
                            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                                 alt="{{ __('gemini-landing.home.hero_image_alt') }}"
                                 class="w-full h-auto object-cover opacity-90 hover:opacity-100 transition duration-500">
                        </div>

                        <div class="absolute -bottom-6 -left-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-12 h-12 bg-brand-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-brand-600 dark:text-brand-400 text-xl">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">{{ __('gemini-landing.home.growth_label') }}</p>
                                <p class="text-lg font-bold text-brand-dark dark:text-white">+128%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="about" class="py-24 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="md:w-1/2" data-aos="fade-up">
                    <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="{{ __('gemini-landing.home.about_image_alt') }}" class="rounded-3xl shadow-2xl opacity-90 dark:opacity-80">
                </div>
                <div class="md:w-1/2" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-3xl lg:text-4xl font-bold text-brand-dark dark:text-white mb-6">{{ __('gemini-landing.home.about_heading') }}</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-lg mb-6 leading-relaxed">
                        {!! __('gemini-landing.home.about_p1', ['efficiency' => __('gemini-landing.home.efficiency')]) !!}
                    </p>
                    <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                        {{ __('gemini-landing.home.about_p2') }}
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-brand-500 text-xl"></i>
                            <span class="font-semibold text-brand-dark dark:text-white">{{ __('gemini-landing.home.about_bullet_1') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-brand-500 text-xl"></i>
                            <span class="font-semibold text-brand-dark dark:text-white">{{ __('gemini-landing.home.about_bullet_2') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-brand-500 text-xl"></i>
                            <span class="font-semibold text-brand-dark dark:text-white">{{ __('gemini-landing.home.about_bullet_3') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-slate-50 dark:bg-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-3xl lg:text-4xl font-bold text-brand-dark dark:text-white mb-4">{{ __('gemini-landing.home.features_heading') }}</h2>
                <p class="text-slate-500 dark:text-slate-400">{{ __('gemini-landing.home.features_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border-b-4 border-brand-500 group" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-slate-800 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <h3 itemprop="name" class="text-xl font-bold text-brand-dark dark:text-white mb-3">{{ __('gemini-landing.home.feature_hr_title') }}</h3>
                    <p itemprop="description" class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ __('gemini-landing.home.feature_hr_desc') }}</p>
                </div>

                <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border-b-4 border-brand-accent group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-yellow-50 dark:bg-slate-800 text-yellow-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3 itemprop="name" class="text-xl font-bold text-brand-dark dark:text-white mb-3">{{ __('gemini-landing.home.feature_booking_title') }}</h3>
                    <p itemprop="description" class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ __('gemini-landing.home.feature_booking_desc') }}</p>
                </div>

                <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border-b-4 border-brand-500 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-brand-100 dark:bg-slate-800 text-brand-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                    <h3 itemprop="name" class="text-xl font-bold text-brand-dark dark:text-white mb-3">{{ __('gemini-landing.home.feature_pos_title') }}</h3>
                    <p itemprop="description" class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ __('gemini-landing.home.feature_pos_desc') }}</p>
                </div>

                <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border-b-4 border-slate-800 dark:border-slate-600 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-slate-800 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h3 itemprop="name" class="text-xl font-bold text-brand-dark dark:text-white mb-3">{{ __('gemini-landing.home.feature_erp_title') }}</h3>
                    <p itemprop="description" class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ __('gemini-landing.home.feature_erp_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="py-24 bg-brand-900 dark:bg-slate-950 text-white relative transition-colors duration-300">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-white">{{ __('gemini-landing.home.testimonials_heading') }}</h2>
                    <p class="text-brand-100 mt-2">{{ __('gemini-landing.home.testimonials_subtitle') }}</p>
                </div>
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

                $images['female'] = $images['famale'];

                $reviewsRaw = trans('gemini-landing.home.reviews');
                if (!is_array($reviewsRaw)) {
                    $reviewsRaw = [];
                }

                $reviews = array_map(function ($review) use ($images) {
                    $avatarKey = $review['avatar'] ?? 'male';
                    $review['avatar'] = $images[$avatarKey] ?? $images['male'];
                    $review['rating'] = max(1, min(5, (int) ($review['rating'] ?? 5)));
                    $review['dir'] = $review['dir'] ?? 'ltr';
                    return $review;
                }, $reviewsRaw);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($reviews as $review)
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl border border-white/10" itemscope itemtype="https://schema.org/Review" dir="{{ $review['dir'] }}">
                        <span class="hidden" itemprop="itemReviewed" itemscope itemtype="https://schema.org/SoftwareApplication">
                            <meta itemprop="name" content="Mohaaseb ERP">
                            <meta itemprop="applicationCategory" content="BusinessApplication">
                        </span>
                        <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                            <meta itemprop="ratingValue" content="{{ $review['rating'] }}">
                            <meta itemprop="bestRating" content="5">
                        </span>
                        <div class="flex text-brand-accent mb-4">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= $review['rating'] ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-brand-50 italic mb-6"  itemprop="reviewBody">"{{ $review['body'] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center font-bold text-white">{{ substr($review['name'], 0, 2) }}</div>
                            <div  itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <h4 class="font-bold text-white"  itemprop="name">{{ $review['name'] }}</h4>
                                <p class="text-xs text-brand-100"  itemprop="jobTitle">{{ $review['job_title'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="blog" class="py-24 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-brand-dark dark:text-white">{{ __('gemini-landing.home.blog_heading') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2">{{ __('gemini-landing.home.blog_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($blogs as $blog)
                    <article class="group cursor-pointer" data-aos="fade-up"  itemscope itemtype="https://schema.org/BlogPosting">
                        <div class="overflow-hidden rounded-2xl mb-4 h-52">
                            <img itemprop="image" src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 dark:opacity-80" alt="{{ __('gemini-landing.home.blog_image_alt') }}">
                        </div>
                        <span class="text-xs font-bold text-brand-500 uppercase">{{ $blog->category?->name ?? __('gemini-landing.home.blog_fallback_category') }}</span>
                        <h3  itemprop="headline" class="text-xl font-bold text-brand-dark dark:text-white mt-2 mb-2 group-hover:text-brand-500 transition-colors">{{ $blog->title }}</h3>
                        <p  itemprop="description" class="text-slate-500 dark:text-slate-400 text-sm line-clamp-2">{{ \Illuminate\Support\Str::limit($blog->excerpt ?: strip_tags($blog->content), 120) }}</p>
                        <a href="{{ route('blogs.show', ['slug' => $blog->slug, 'lang' => $__currentLang]) }}" aria-label="{{ __('gemini-landing.home.blog_read_more_aria', ['title' => $blog->title]) }}" class="inline-block mt-4 text-brand-600 dark:text-brand-400 font-bold hover:underline">{{ __('gemini-landing.home.blog_read_more') }}</a>
                        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" class="hidden">
                            <meta itemprop="name" content="Mohaaseb">
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="py-24 bg-gradient-to-br from-brand-dark to-slate-900 text-white relative overflow-hidden">
        <div class="absolute -right-20 top-0 w-96 h-96 bg-brand-500 opacity-20 blur-[100px] rounded-full"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row gap-16">
                <div class="lg:w-1/2" data-aos="fade-right">
                    <h2 class="text-4xl font-bold mb-6">{{ __('gemini-landing.home.contact_heading') }}</h2>
                    <p class="text-slate-300 text-lg mb-8">{{ __('gemini-landing.home.contact_subtitle') }}</p>

                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-brand-400 text-xl"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase">{{ __('gemini-landing.home.contact_call_us') }}</p>
                                <p class="text-lg font-bold">{{ __('gemini-landing.contact_info.phone') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-brand-400 text-xl"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase">{{ __('gemini-landing.home.contact_email_us') }}</p>
                                <p class="text-lg font-bold">{{ __('gemini-landing.contact_info.sales_email') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2" data-aos="fade-left">
                    <form  action="{{ route('contact-us') }}" method="POST" class="bg-white dark:bg-slate-800 p-8 rounded-3xl text-slate-700 dark:text-slate-300 shadow-2xl transition-colors duration-300">
                        @csrf
                        <h3 class="text-2xl font-bold text-brand-dark dark:text-white mb-6">{{ __('gemini-landing.home.contact_form_heading') }}</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_first_name') }}</label>
                                <input type="text" name="fname" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_first_name') }}" required>
                                @error('fname')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_last_name') }}</label>
                                <input type="text" name="lname" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_last_name') }}" required>
                                @error('lname')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_phone') }}</label>
                                <input type="text" name="phone" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_phone') }}" required>
                                @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_email') }}</label>
                                <input type="email" name="email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_email') }}" required>
                                @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_message') }}</label>
                            <textarea name="message" rows="3" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_message') }}"></textarea>
                            @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold rounded-xl hover:bg-brand-600 transition shadow-lg">{{ __('gemini-landing.home.contact_submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
