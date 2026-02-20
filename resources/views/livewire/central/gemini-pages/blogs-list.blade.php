<div>
<header class="pt-32 pb-16 bg-gradient-to-b from-brand-50 to-white dark:from-slate-900 dark:to-slate-800 text-center transition-colors duration-300">
        <div class="container mx-auto px-6" data-aos="fade-up">
            <p class="text-brand-500 font-bold uppercase tracking-wide text-sm mb-2">{{ __('gemini-landing.blogs_page.badge') }}</p>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-6">{{ __('gemini-landing.blogs_page.title') }}</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-8">
                {{ __('gemini-landing.blogs_page.subtitle') }}
            </p>

            <div class="max-w-xl mx-auto relative">
                <input type="text" wire:model.live="q" placeholder="{{ __('gemini-landing.blogs_page.search_placeholder') }}" class="w-full py-4 pl-6 pr-12 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-white shadow-sm focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 dark:focus:ring-brand-900 transition">
                <button class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-500">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>
            </div>
        </div>
    </header>

    <section class="py-12 bg-white dark:bg-slate-800 transition-colors duration-300">
        @if($featured)
        <div class="container mx-auto px-6">
            <div class="bg-brand-900 dark:bg-slate-700 rounded-3xl overflow-hidden shadow-2xl flex flex-col lg:flex-row" data-aos="zoom-in">
                <div class="lg:w-1/2 relative min-h-[300px]">
                    <img src="{{ $featured->image_path }}" alt="{{ __('gemini-landing.blogs_page.featured_alt') }}" class="absolute inset-0 w-full h-full object-cover opacity-80 hover:opacity-100 transition duration-700">
                </div>
                <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center text-white">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-brand-500 text-xs font-bold px-3 py-1 rounded-full text-white">{{ __('gemini-landing.blogs_page.featured_label') }}</span>
                        <span class="text-slate-300 text-sm"><i class="fa-regular fa-clock mr-1"></i> {{ __('gemini-landing.blogs.reading_time',['time'=>$featured->reading_time]) }}</span>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 leading-tight text-white">{{ $featured->title }}</h2>
                    <p class="text-brand-100 dark:text-slate-300 mb-6 leading-relaxed">
                        {{ $featured->excerpt }}
                    </p>
                    <a href="{{ route('blogs.show', $featured->slug) }}" class="inline-flex items-center font-bold text-brand-400 hover:text-white transition">
                        {{ __('gemini-landing.blogs.read_full') }} <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </section>

    <section class="py-16 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-center gap-4 mb-12" data-aos="fade-up">
                <button class="px-6 py-2 rounded-full bg-brand-500 text-white font-semibold shadow-lg shadow-brand-500/30">{{ __('gemini-landing.blogs_page.categories.all') }}</button>
                <button class="px-6 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-medium hover:border-brand-500 hover:text-brand-500 dark:hover:text-brand-400 transition">{{ __('gemini-landing.blogs_page.categories.accounting') }}</button>
                <button class="px-6 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-medium hover:border-brand-500 hover:text-brand-500 dark:hover:text-brand-400 transition">{{ __('gemini-landing.blogs_page.categories.hr_payroll') }}</button>
                <button class="px-6 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-medium hover:border-brand-500 hover:text-brand-500 dark:hover:text-brand-400 transition">{{ __('gemini-landing.blogs_page.categories.retail_pos') }}</button>
                <button class="px-6 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-medium hover:border-brand-500 hover:text-brand-500 dark:hover:text-brand-400 transition">{{ __('gemini-landing.blogs_page.categories.success_stories') }}</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($blogs as $blog)
                    <article class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group border border-transparent dark:border-slate-700" data-aos="fade-up" data-aos-delay="0">
                        <div class="h-48 overflow-hidden relative">
                            <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-brand-600 uppercase">{{ $blog->category?->name ?? __('gemini-landing.blogs_page.card_category') }}</div>
                            <img src="{{ $blog->image_path }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 dark:opacity-80" alt="{{ $blog->title }}">
                        </div>
                        <div class="p-6">
                            <div class="flex items-center text-xs text-slate-400 mb-3 gap-4">
                                <span>{{ carbon($blog->published_at)->format('M d, Y') }}</span>
                                <span>• {{ __('gemini-landing.blogs_page.by_admin') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-3 group-hover:text-brand-500 transition-colors">{{ $blog->title }}</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-3">{{ $blog->excerpt }}</p>
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-brand-600 dark:text-brand-400 font-bold text-sm hover:underline">{{ __('gemini-landing.blogs.read_full') }} &rarr;</a>
                        </div>
                    </article>
                @empty
                    <p class="text-center text-slate-500 dark:text-slate-400 col-span-full">{{ __('gemini-landing.blogs.no_blogs') }}</p>
                @endforelse
            </div>

            <div class="mt-12 flex justify-center" data-aos="fade-up" data-aos-delay="200">
                {{ $blogs->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
</div>
