@extends('layouts.central.gemini.layout')

@section('content')
<main itemscope itemtype="https://schema.org/WebPage">
<header class="pt-32 pb-12 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6 max-w-4xl text-center" data-aos="fade-up">

            <nav class="flex items-center justify-center gap-2 text-sm text-slate-400 mb-6 font-medium" aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                <a href="{{ route('blogs.index') }}" class="hover:text-brand-500" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name">{{ __('gemini-landing.blog_details.breadcrumb_blog') }}</span>
                    <meta itemprop="position" content="1">
                    <meta itemprop="item" content="{{ route('blogs.index') }}">
                </a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-brand-500 font-bold" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name">{{ $blog->category?->name ?? __('gemini-landing.blog_details.breadcrumb_category') }}</span>
                    <meta itemprop="position" content="2">
                </span>
            </nav>

            <h1 class="text-3xl md:text-5xl font-extrabold text-brand-dark dark:text-white mb-6 leading-tight">
                {{ $blog->title }}
            </h1>

            <div class="flex items-center justify-center gap-4 mb-8">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="{{ __('gemini-landing.blog_details.author_alt') }}" class="w-12 h-12 rounded-full border-2 border-white dark:border-slate-700 shadow-sm">
                <div class="text-left">
                    <p class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ __('gemini-landing.mohaaseb') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('gemini-landing.common.admin') }} • {{ __('gemini-landing.blogs.reading_time', ['time' => $blog->reading_time]) }}</p>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-2xl relative group">
                <img src="{{ $blog->image_path }}" alt="{{ $blog->title }}" class="w-full h-auto transform group-hover:scale-105 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            </div>
        </div>
    </header>

    <div class="bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6 py-12 max-w-6xl">
            <div class="flex flex-col lg:flex-row gap-12">

                <div class="lg:w-1/12 hidden lg:flex flex-col items-center gap-6 sticky top-32 h-fit">
                    <span class="text-xs font-bold text-slate-400 uppercase rotate-180" style="writing-mode: vertical-rl;">{{ __('gemini-landing.blog_details.share_article') }}</span>
                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-700"></div>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-[#1877F2] hover:text-white transition"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-[#1DA1F2] hover:text-white transition"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-[#0A66C2] hover:text-white transition"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-green-500 hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></a>
                </div>

                <article class="lg:w-8/12 prose max-w-none font-serif text-lg text-slate-600 dark:text-slate-300">
                    {!! $blog->content !!}
                </article>

                <aside class="lg:w-3/12 space-y-8">

                    {{-- <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 sticky top-32">
                        <h4 class="font-bold text-brand-dark dark:text-white mb-4 uppercase text-xs tracking-wider">Table of Contents</h4>
                        <ul class="space-y-3 text-sm text-slate-500 dark:text-slate-400">
                            <li><a href="#what-is-phase-2" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">What is Phase 2?</a></li>
                            <li><a href="#key-differences" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">Key Differences</a></li>
                            <li><a href="#how-to-prepare" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">How to Prepare</a></li>
                            <li><a href="#solution" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">The Automated Solution</a></li>
                            <li><a href="#conclusion" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">Conclusion</a></li>
                        </ul>
                    </div> --}}

                    <div class="bg-brand-900 dark:bg-slate-800 text-white p-8 rounded-2xl text-center border dark:border-slate-700">
                        <div class="w-12 h-12 bg-brand-500 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                            <i class="fa-regular fa-paper-plane"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-2">{{ __('gemini-landing.blog_details.weekly_insights_title') }}</h4>
                        <p class="text-brand-100 dark:text-slate-400 text-sm mb-4">{{ __('gemini-landing.blog_details.weekly_insights_subtitle') }}</p>
                        <input type="email" placeholder="{{ __('gemini-landing.blog_details.email_address') }}" class="w-full px-4 py-3 rounded-lg text-slate-800 mb-3 text-sm focus:outline-none border-none">
                        <button class="w-full bg-brand-500 py-3 rounded-lg font-bold text-sm hover:bg-brand-400 transition">{{ __('gemini-landing.blog_details.subscribe') }}</button>
                    </div>

                </aside>
            </div>
        </div>
    </div>

    <section class="py-16 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-6 max-w-6xl">
            <h3 class="text-2xl font-bold text-brand-dark dark:text-white mb-8">{{ __('gemini-landing.blog_details.read_next') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($readNextBlogs as $blog)
                    <a href="{{ route('blogs.show', $blog->slug) }}" class="group block">
                        <div class="overflow-hidden rounded-xl mb-4 h-48">
                            <img src="{{ $blog->image_path }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-90 dark:opacity-80">
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-brand-500 transition">{{ $blog->title }}</h4>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
