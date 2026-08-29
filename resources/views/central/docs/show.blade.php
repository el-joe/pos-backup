@extends('layouts.central.gemini.layout')

@push('styles')
    <style>
        .docs-content h1, .docs-content h2, .docs-content h3, .docs-content h4 {
            font-weight: 800;
            color: #0f172a;
            margin-top: 1.75rem;
            margin-bottom: 0.75rem;
            scroll-margin-top: 6rem;
        }
        html.dark .docs-content h1, html.dark .docs-content h2, html.dark .docs-content h3, html.dark .docs-content h4 {
            color: #fff;
        }
        .docs-content h2 { font-size: 1.5rem; }
        .docs-content h3 { font-size: 1.25rem; }
        .docs-content h4 { font-size: 1.1rem; }
        .docs-content p { margin-bottom: 1rem; }
        .docs-content ul, .docs-content ol { margin: 0 0 1rem 1.25rem; }
        .docs-content ul { list-style: disc; }
        .docs-content ol { list-style: decimal; }
        .docs-content li { margin-bottom: 0.4rem; }
        .docs-content a { color: #00d2b4; font-weight: 600; text-decoration: underline; }
        .docs-content strong { color: #0f172a; }
        html.dark .docs-content strong { color: #f1f5f9; }
        .docs-content img { border-radius: 0.75rem; margin: 1rem 0; }
        .docs-content blockquote {
            border-inline-start: 4px solid #00d2b4;
            padding-inline-start: 1rem;
            font-style: italic;
            color: #475569;
            margin: 1rem 0;
        }
        html.dark .docs-content blockquote { color: #cbd5e1; }
    </style>
@endpush

@section('content')
    @php
        $videos = collect($page->youtube_videos ?? []);
        $topVideos = $videos->where('position', 'top')->values();
        $middleVideos = $videos->where('position', 'middle')->values();
        $bottomVideos = $videos->where('position', 'bottom')->values();

        $locale = app()->getLocale();
        $ordered = $relatedPages->push($page)->sortBy('sort_order')->values();
        $pos = $ordered->search(fn ($p) => $p->id === $page->id);
        $prevPage = $pos !== false && $pos > 0 ? $ordered->get($pos - 1) : null;
        $nextPage = $pos !== false && $pos < $ordered->count() - 1 ? $ordered->get($pos + 1) : null;

        $rendered = $page->getContentWithToc();
        $toc = $rendered['toc'];
    @endphp

    <main>
        <header class="pt-32 pb-10 bg-gradient-to-b from-brand-50 to-white dark:from-slate-900 dark:to-slate-800 transition-colors duration-300">
            <div class="container mx-auto px-6" data-aos="fade-up">
                <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-4">
                    <a href="{{ route('docs.index') }}" class="hover:text-brand-500">{{ __('Documentation') }}</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-brand-dark dark:text-white font-medium">{{ $page->title }}</span>
                </nav>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-brand-dark dark:text-white">{{ $page->title }}</h1>
                @if ($page->short_description)
                    <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mt-4">{{ $page->short_description }}</p>
                @endif
            </div>
        </header>

        <section class="py-12 bg-white dark:bg-slate-900 min-h-screen transition-colors duration-300">
            <div class="container mx-auto px-6">
                <div class="flex flex-col lg:flex-row gap-10">
                    <aside class="lg:w-1/4 lg:shrink-0 space-y-6">
                        <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 lg:sticky lg:top-32">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4">
                                {{ __('In this section') }}
                            </h4>
                            <ul class="space-y-1">
                                @foreach ($ordered as $item)
                                    <li>
                                        <a href="{{ route('docs.show', ['slug' => $item->slug]) }}"
                                           class="block px-3 py-2 rounded-lg text-sm transition {{ $item->id === $page->id ? 'bg-brand-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 hover:text-brand-500' }}">
                                            {{ $item->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            @if (count($toc))
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mt-6 mb-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                                    {{ __('On this page') }}
                                </h4>
                                <ul class="space-y-1">
                                    @foreach ($toc as $entry)
                                        <li>
                                            <a href="#{{ $entry['id'] }}"
                                               class="block rounded-lg text-sm text-slate-500 dark:text-slate-400 hover:text-brand-500 transition py-1"
                                               style="padding-inline-start: {{ ($entry['level'] - 2) * 0.75 + 0.5 }}rem">
                                                {{ $entry['text'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </aside>

                    <article class="lg:w-3/4 min-w-0">
                        @foreach ($topVideos as $video)
                            @if ($locale === 'ar' && $video['description_ar'])
                                <p class="text-slate-600 dark:text-slate-300 mb-2">{{ $video['description_ar'] }}</p>
                            @elseif ($video['description_en'])
                                <p class="text-slate-600 dark:text-slate-300 mb-2">{{ $video['description_en'] }}</p>
                            @endif
                            <x-youtube-embed :url="$page->getYoutubeEmbedUrl($video['url'])"
                                              :title="$locale === 'ar' ? ($video['title_ar'] ?: $video['title_en']) : ($video['title_en'] ?: $video['title_ar'])" />
                        @endforeach

                        <div class="docs-content max-w-none text-slate-600 dark:text-slate-300 leading-relaxed">
                            {!! $rendered['html'] !!}
                        </div>

                        @foreach ($middleVideos as $video)
                            @if ($locale === 'ar' && $video['description_ar'])
                                <p class="text-slate-600 dark:text-slate-300 mb-2">{{ $video['description_ar'] }}</p>
                            @elseif ($video['description_en'])
                                <p class="text-slate-600 dark:text-slate-300 mb-2">{{ $video['description_en'] }}</p>
                            @endif
                            <x-youtube-embed :url="$page->getYoutubeEmbedUrl($video['url'])"
                                              :title="$locale === 'ar' ? ($video['title_ar'] ?: $video['title_en']) : ($video['title_en'] ?: $video['title_ar'])" />
                        @endforeach

                        @foreach ($bottomVideos as $video)
                            @if ($locale === 'ar' && $video['description_ar'])
                                <p class="text-slate-600 dark:text-slate-300 mb-2">{{ $video['description_ar'] }}</p>
                            @elseif ($video['description_en'])
                                <p class="text-slate-600 dark:text-slate-300 mb-2">{{ $video['description_en'] }}</p>
                            @endif
                            <x-youtube-embed :url="$page->getYoutubeEmbedUrl($video['url'])"
                                              :title="$locale === 'ar' ? ($video['title_ar'] ?: $video['title_en']) : ($video['title_en'] ?: $video['title_ar'])" />
                        @endforeach

                        <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-700 flex flex-wrap items-center gap-3">
                            <span class="text-slate-500 dark:text-slate-400 text-sm">{{ __('Was this helpful?') }}</span>
                            <button type="button" class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-green-500 hover:text-white transition docs-feedback-btn" data-helpful="1">
                                <i class="fa-regular fa-thumbs-up"></i>
                            </button>
                            <button type="button" class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-red-500 hover:text-white transition docs-feedback-btn" data-helpful="0">
                                <i class="fa-regular fa-thumbs-down"></i>
                            </button>
                            <span id="docs-feedback-message" class="text-sm text-brand-500 font-medium"></span>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                            @if ($prevPage)
                                <a href="{{ route('docs.show', ['slug' => $prevPage->slug]) }}"
                                   class="inline-flex items-center gap-2 px-5 py-3 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-brand-500 hover:text-brand-500 transition text-sm font-bold">
                                    <i class="fa-solid fa-arrow-left"></i> {{ $prevPage->title }}
                                </a>
                            @else
                                <span></span>
                            @endif

                            @if ($nextPage)
                                <a href="{{ route('docs.show', ['slug' => $nextPage->slug]) }}"
                                   class="inline-flex items-center gap-2 px-5 py-3 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-brand-500 hover:text-brand-500 transition text-sm font-bold ms-auto">
                                    {{ $nextPage->title }} <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            document.querySelectorAll('.docs-feedback-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    fetch('{{ route('docs.feedback') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            slug: @json($page->slug),
                            helpful: btn.dataset.helpful === '1',
                        }),
                    }).then(function () {
                        document.getElementById('docs-feedback-message').textContent = '{{ __('Thanks for your feedback!') }}';
                    }).catch(function () {});
                });
            });
        </script>
    @endpush
@endsection
