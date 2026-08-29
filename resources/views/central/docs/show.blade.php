@extends('layouts.central.gemini.layout')

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
    @endphp

    <main>
        <header class="pt-32 pb-10 bg-gradient-to-b from-brand-50 to-white dark:from-slate-900 dark:to-slate-800 transition-colors duration-300">
            <div class="container mx-auto px-6" data-aos="fade-up">
                <nav class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                    <a href="{{ route('docs.index') }}" class="hover:text-brand-500">{{ __('Documentation') }}</a>
                    <span class="mx-2">/</span>
                    <span class="text-brand-dark dark:text-white">{{ $page->title }}</span>
                </nav>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-brand-dark dark:text-white">{{ $page->title }}</h1>
                @if ($page->short_description)
                    <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mt-4">{{ $page->short_description }}</p>
                @endif
            </div>
        </header>

        <section class="py-12 bg-white dark:bg-slate-900 min-h-screen transition-colors duration-300">
            <div class="container mx-auto px-6">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 sticky-top" style="top: 100px;">
                            <h4 class="text-sm font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-3">
                                {{ __('In this section') }}
                            </h4>
                            <ul class="list-unstyled mb-0">
                                @foreach ($ordered as $item)
                                    <li class="mb-2">
                                        <a href="{{ route('docs.show', ['slug' => $item->slug]) }}"
                                           class="d-block px-2 py-1 rounded {{ $item->id === $page->id ? 'bg-brand-500 text-white' : 'text-slate-700 dark:text-slate-300 hover:text-brand-500' }}">
                                            {{ $item->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        @foreach ($topVideos as $video)
                            @if ($locale === 'ar' && $video['description_ar'])
                                <p class="text-slate-600 dark:text-slate-300 mb-2">{{ $video['description_ar'] }}</p>
                            @elseif ($video['description_en'])
                                <p class="text-slate-600 dark:text-slate-300 mb-2">{{ $video['description_en'] }}</p>
                            @endif
                            <x-youtube-embed :url="$page->getYoutubeEmbedUrl($video['url'])"
                                              :title="$locale === 'ar' ? ($video['title_ar'] ?: $video['title_en']) : ($video['title_en'] ?: $video['title_ar'])" />
                        @endforeach

                        <div class="prose dark:prose-invert max-w-none fs-16px text-body">
                            {!! $page->content !!}
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

                        <div class="mt-5 pt-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-slate-500 dark:text-slate-400">{{ __('Was this helpful?') }}</span>
                                <button type="button" class="btn btn-sm btn-outline-success docs-feedback-btn" data-helpful="1">
                                    <i class="fa-regular fa-thumbs-up"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger docs-feedback-btn" data-helpful="0">
                                    <i class="fa-regular fa-thumbs-down"></i>
                                </button>
                                <span id="docs-feedback-message" class="text-sm text-slate-500 dark:text-slate-400"></span>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between flex-wrap gap-3">
                            @if ($prevPage)
                                <a href="{{ route('docs.show', ['slug' => $prevPage->slug]) }}" class="btn btn-outline-theme">
                                    <i class="fa fa-arrow-left"></i> {{ $prevPage->title }}
                                </a>
                            @else
                                <span></span>
                            @endif

                            @if ($nextPage)
                                <a href="{{ route('docs.show', ['slug' => $nextPage->slug]) }}" class="btn btn-outline-theme ms-auto">
                                    {{ $nextPage->title }} <i class="fa fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
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
